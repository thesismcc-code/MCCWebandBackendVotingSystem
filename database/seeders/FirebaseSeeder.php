<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class FirebaseSeeder extends Seeder
{
    protected Database $db;

    /** Computed in seedVotes() and reused in seedReports() for consistent turnout stats. */
    private int $activeVoterCount = 0;
    private int $totalStudentCount = 0;

    // ─── LOGGING HELPERS ────────────────────────────────────────────────────────

    /** Green  — record written, section done */
    private function logSuccess(string $message): void
    {
        $this->command->line("  <fg=green>{$message}</>");
        Log::info($message);
    }

    /** Yellow — non-fatal notice, skipped item */
    private function logWarning(string $message): void
    {
        $this->command->line("  <fg=yellow>{$message}</>");
        Log::warning($message);
    }

    /** Red    — something went wrong */
    private function logError(string $message): void
    {
        $this->command->line("  <fg=red>{$message}</>");
        Log::error($message);
    }

    /** Default terminal color — section headers, progress notes */
    private function logInfo(string $message): void
    {
        $this->command->line("  {$message}");
        Log::info($message);
    }

    // ─── FIREBASE INIT ──────────────────────────────────────────────────────────

    private function initFirebase(): void
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');
        $databaseUrl     = env('FIREBASE_DATABASE_URL');

        if (! $credentialsPath) {
            $msg = 'FIREBASE_CREDENTIALS is not set in your .env file. Example: FIREBASE_CREDENTIALS=storage/app/firebase/serviceAccountKey.json';
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        if (! $databaseUrl) {
            $msg = 'FIREBASE_DATABASE_URL is not set in your .env file. Example: FIREBASE_DATABASE_URL=https://your-project-id-default-rtdb.firebaseio.com';
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        $absolutePath = base_path($credentialsPath);

        if (! file_exists($absolutePath)) {
            $msg = "Firebase credentials file not found at: {$absolutePath}. Make sure you placed serviceAccountKey.json at: {$credentialsPath}";
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        $factory = (new Factory)
            ->withServiceAccount($absolutePath)
            ->withDatabaseUri($databaseUrl);

        $this->db = $factory->createDatabase();
    }

    // ─── ENTRY POINT ────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->initFirebase();

        $this->logInfo('');
        $this->logInfo('Seeding Firebase Realtime Database...');
        $this->logInfo('');

        $this->seedUsers();
        $this->seedElections();
        $this->seedPartyLists();
        $this->seedCandidates();
        $this->seedVotes();
        $this->seedReports();
        $this->seedSystemLogs();

        $this->logInfo('');
        $this->logSuccess('Firebase seeding complete!');
        $this->logInfo('');
    }

    // ─── WRITE HELPER ───────────────────────────────────────────────────────────

    private function set(string $collection, string $key, array $data): void
    {
        try {
            $this->db->getReference("/{$collection}/{$key}")->set($data);
            $this->logSuccess("Written: /{$collection}/{$key}");
        } catch (\Throwable $e) {
            $this->logError("Failed to write /{$collection}/{$key} — {$e->getMessage()}");
        }
    }

    // ─── DATE HELPERS ───────────────────────────────────────────────────────────

    private function now(): string
    {
        return now()->toISOString();
    }

    private function daysAgo(int $days): string
    {
        return now()->subDays($days)->toISOString();
    }

    private function daysFromNow(int $days): string
    {
        return now()->addDays($days)->toISOString();
    }

    private function randomDateBetween(string $start, string $end): string
    {
        $startTs = strtotime($start);
        $endTs   = strtotime($end);
        return date('Y-m-d\TH:i:s', rand($startTs, $endTs));
    }

    private function generateStudentId(int $seq): string
    {
        $yearSuffix = substr(date('Y'), 1);
        return 'STU-' . $yearSuffix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    private function generateUserId(string $role): string
    {
        $prefix = match ($role) {
            'admin'   => 'ADM',
            'student' => 'STU',
            'teacher' => 'THR',
            'sao'     => 'SAO',
            default   => 'USR',
        };
        return $prefix . Str::random(12);
    }

    // ─── COLLECTIONS ────────────────────────────────────────────────────────────

    private function seedUsers(): void
    {
        $this->logInfo('Seeding /users...');

        $password = Hash::make('password123');

        // ── Fixed staff accounts ──────────────────────────────────────────────
        $staff = [
            'ADMaB3kL9mNpQr' => ['first_name' => 'Alice',  'middle_name' => 'Marie', 'last_name' => 'Santos',    'email' => 'alice.santos@school.edu',   'role' => 'admin',   'student_id' => null, 'teacher_id' => null,             'admin_id' => 'ADMaB3kL9mNpQr'],
            'SAOxK7wP2dYcHj' => ['first_name' => 'Bob',    'middle_name' => 'Cruz',  'last_name' => 'Reyes',     'email' => 'bob.reyes@school.edu',      'role' => 'sao',     'student_id' => null, 'teacher_id' => 'SAOxK7wP2dYcHj', 'admin_id' => null],
            'THRmN4vZ8qEtWs' => ['first_name' => 'Carlos', 'middle_name' => 'Jose',  'last_name' => 'Dela Cruz', 'email' => 'carlos.delacruz@school.edu','role' => 'teacher', 'student_id' => null, 'teacher_id' => 'THRmN4vZ8qEtWs', 'admin_id' => null],
        ];

        foreach ($staff as $key => $user) {
            $this->set('users', $key, array_merge($user, [
                'id'                => $key,
                'password'          => $password,
                'email_verified_at' => $this->now(),
                'remember_token'    => null,
                'created_at'        => $this->daysAgo(90),
                'updated_at'        => $this->daysAgo(90),
            ]));
        }

        // ── 300 student accounts ─────────────────────────────────────────────
        $firstNames  = ['Juan','Maria','Jose','Ana','Miguel','Rosa','Carlos','Luz','Ramon','Elena','Pedro','Sofia','Luis','Carmen','Antonio','Isabel','Diego','Patricia','Eduardo','Monica','Felix','Jasmine','Cedric','Janine','Ryan','Aileen','Mark','Christine','Kevin','Trisha'];
        $middleNames = ['Santos','Reyes','Cruz','Dela Cruz','Garcia','Mendoza','Lopez','Torres','Hernandez','Flores'];
        $lastNames   = ['Bautista','Villanueva','Ramos','Castro','Aquino','Gonzales','Diaz','Marquez','Quispe','Lim','Tan','Go','Chan','Uy','Chua','Sy','Ko','Ng','Yu','Dee'];

        $this->logInfo('Generating 300 student accounts...');

        for ($i = 1; $i <= 300; $i++) {
            $userId = $this->generateUserId('student');
            $this->set('users', $userId, [
                'id'                => $userId,
                'first_name'        => $firstNames[array_rand($firstNames)],
                'middle_name'       => $middleNames[array_rand($middleNames)],
                'last_name'         => $lastNames[array_rand($lastNames)],
                'email'             => 'student' . str_pad($i, 4, '0', STR_PAD_LEFT) . '@school.edu',
                'password'          => $password,
                'role'              => 'student',
                'student_id'        => $this->generateStudentId($i),
                'teacher_id'        => null,
                'admin_id'          => null,
                'email_verified_at' => $this->daysAgo(rand(10, 180)),
                'remember_token'    => null,
                'created_at'        => $this->daysAgo(rand(10, 180)),
                'updated_at'        => $this->daysAgo(rand(1, 10)),
            ]);
        }

        $this->logSuccess('300 students seeded successfully.');
    }

    private function seedElections(): void
    {
        $this->logInfo('Seeding /elections...');

        $elections = [
            'election_001' => [
                'id'            => 'election_001',
                'election_name' => 'SSC General Elections 2024',
                'description'   => 'Annual Supreme Student Council election open to all enrolled students.',
                'start_date'    => $this->daysAgo(10),
                'end_date'      => $this->daysAgo(3),
                'status'        => 'closed',
                'created_at'    => $this->daysAgo(30),
                'updated_at'    => $this->daysAgo(3),
            ],
            'election_002' => [
                'id'            => 'election_002',
                'election_name' => 'SSC By-Elections 2024',
                'description'   => 'By-election for vacated SSC positions.',
                'start_date'    => $this->daysFromNow(5),
                'end_date'      => $this->daysFromNow(7),
                'status'        => 'upcoming',
                'created_at'    => $this->now(),
                'updated_at'    => $this->now(),
            ],
            'election_003' => [
                'id'            => 'election_003',
                'election_name' => 'College of Engineering Elections 2024',
                'description'   => 'Department-level student council election.',
                'start_date'    => $this->daysAgo(1),
                'end_date'      => $this->daysFromNow(1),
                'status'        => 'active',
                'created_at'    => $this->daysAgo(20),
                'updated_at'    => $this->daysAgo(1),
            ],
        ];

        foreach ($elections as $key => $election) {
            $this->set('elections', $key, $election);
        }

        $this->logSuccess('Elections seeded successfully.');
    }

    private function seedPartyLists(): void
    {
        $this->logInfo('Seeding /party_lists...');

        $parties = [
            'party_001' => ['id' => 'party_001', 'party_name' => 'Unity Party',      'description' => 'Committed to unity and inclusive student governance.',  'logo' => 'logos/unity_party.png',       'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
            'party_002' => ['id' => 'party_002', 'party_name' => 'Progreso Alliance', 'description' => 'Advocates for academic excellence and student welfare.', 'logo' => 'logos/progreso_alliance.png', 'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
            'party_003' => ['id' => 'party_003', 'party_name' => 'Independent',       'description' => 'Non-partisan independent candidates.',                   'logo' => null,                          'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
        ];

        foreach ($parties as $key => $party) {
            $this->set('party_lists', $key, $party);
        }

        $this->logSuccess('Party lists seeded successfully.');
    }

    private function seedCandidates(): void
    {
        $this->logInfo('Seeding /candidates...');

        $candidates = [
            // ── election_001 ─────────────────────────────────────────────────
            'cand_001' => ['id' => 'cand_001', 'user_id' => 'STUrJ6hD1fXbLn', 'party_list_id' => 'party_001', 'election_id' => 'election_001', 'position' => 'President',      'manifesto' => 'Transparency and student welfare in all SSC decisions.',      'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_002' => ['id' => 'cand_002', 'user_id' => 'STUgC5pM3kWoAe', 'party_list_id' => 'party_002', 'election_id' => 'election_001', 'position' => 'President',      'manifesto' => 'Progress through unity — a stronger voice for every student.', 'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_003' => ['id' => 'cand_003', 'user_id' => 'STUyT9nB7rVdQz', 'party_list_id' => 'party_001', 'election_id' => 'election_001', 'position' => 'Vice President', 'manifesto' => 'Bridging the gap between students and administration.',        'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_004' => ['id' => 'cand_004', 'user_id' => 'STUhR2mK4sJuPf', 'party_list_id' => 'party_003', 'election_id' => 'election_001', 'position' => 'Vice President', 'manifesto' => 'An independent voice dedicated solely to student needs.',      'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_005' => ['id' => 'cand_005', 'user_id' => 'STUwL8cF6tNxEv', 'party_list_id' => 'party_002', 'election_id' => 'election_001', 'position' => 'Secretary',      'manifesto' => 'Organized, transparent, and accountable to every student.',   'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_006' => ['id' => 'cand_006', 'user_id' => 'ADMaB3kL9mNpQr', 'party_list_id' => 'party_001', 'election_id' => 'election_001', 'position' => 'Treasurer',      'manifesto' => 'Fiscal responsibility and full financial transparency.',        'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],

            // ── election_003 (active) ─────────────────────────────────────────
            'cand_007' => ['id' => 'cand_007', 'user_id' => 'SAOxK7wP2dYcHj', 'party_list_id' => 'party_001', 'election_id' => 'election_003', 'position' => 'Governor',      'manifesto' => 'Engineering a better tomorrow for all CoE students.',         'status' => 'approved', 'created_at' => $this->daysAgo(15), 'updated_at' => $this->daysAgo(10)],
            'cand_008' => ['id' => 'cand_008', 'user_id' => 'THRmN4vZ8qEtWs', 'party_list_id' => 'party_002', 'election_id' => 'election_003', 'position' => 'Governor',      'manifesto' => 'Stronger labs, stronger students.',                          'status' => 'approved', 'created_at' => $this->daysAgo(15), 'updated_at' => $this->daysAgo(10)],
            'cand_009' => ['id' => 'cand_009', 'user_id' => 'STUrJ6hD1fXbLn', 'party_list_id' => 'party_003', 'election_id' => 'election_003', 'position' => 'Vice Governor', 'manifesto' => 'Bridging CoE students to real industry opportunities.',       'status' => 'approved', 'created_at' => $this->daysAgo(15), 'updated_at' => $this->daysAgo(10)],
            'cand_010' => ['id' => 'cand_010', 'user_id' => 'STUgC5pM3kWoAe', 'party_list_id' => 'party_002', 'election_id' => 'election_003', 'position' => 'Vice Governor', 'manifesto' => 'Student wellness and academic support for every engineer.',    'status' => 'approved', 'created_at' => $this->daysAgo(15), 'updated_at' => $this->daysAgo(10)],
        ];

        foreach ($candidates as $key => $candidate) {
            $this->set('candidates', $key, $candidate);
        }

        $this->logSuccess('Candidates seeded successfully.');
    }

    private function seedVotes(): void
    {
        $this->logInfo('Seeding /votes...');

        $usersSnapshot = $this->db->getReference('/users')->getSnapshot();
        $allStudentIds = [];

        if ($usersSnapshot->exists() && $usersSnapshot->getValue() !== null) {
            foreach ($usersSnapshot->getValue() as $key => $user) {
                if (isset($user['role']) && $user['role'] === 'student') {
                    $allStudentIds[] = $key;
                }
            }
        }

        if (empty($allStudentIds)) {
            $this->logWarning('No student records found in /users. Skipping vote seeding.');
            return;
        }

        shuffle($allStudentIds);
        $totalStudents           = count($allStudentIds);
        $this->totalStudentCount = $totalStudents;
        $this->logInfo("Found {$totalStudents} student(s) to seed votes for.");

        $election001Config = [
            'election_id' => 'election_001',
            'voter_count' => min(300, $totalStudents),
            'start'       => $this->daysAgo(10),
            'end'         => $this->daysAgo(3),
            'positions'   => [
                'President'      => ['cand_001', 'cand_002'],
                'Vice President' => ['cand_003', 'cand_004'],
                'Secretary'      => ['cand_005'],
                'Treasurer'      => ['cand_006'],
            ],
        ];

        // Dynamically cap at 60% of total students so turnout is always realistically
        // below 100% — the election is still ongoing, not everyone has voted yet.
        $activeVoterCount        = (int) floor($totalStudents * 0.60);
        $this->activeVoterCount  = $activeVoterCount;

        $election003Config = [
            'election_id' => 'election_003',
            'voter_count' => $activeVoterCount, // 60% turnout — election is still active/ongoing
            'start'       => $this->daysAgo(1),
            'end'         => $this->now(),
            'positions'   => [
                'Governor'      => ['cand_007', 'cand_008'],
                'Vice Governor' => ['cand_009', 'cand_010'],
            ],
        ];

        $voteCounter = 1;

        foreach ([$election001Config, $election003Config] as $config) {
            $voters        = array_slice($allStudentIds, 0, $config['voter_count']);
            $positionCount = count($config['positions']);

            shuffle($voters);

            $this->logInfo("Generating votes for {$config['election_id']} ({$config['voter_count']} voters x {$positionCount} positions)...");

            foreach ($voters as $voterId) {
                foreach ($config['positions'] as $position => $candidatePool) {
                    $chosenCandidate = $candidatePool[array_rand($candidatePool)];
                    $voteKey         = 'vote_' . str_pad($voteCounter, 5, '0', STR_PAD_LEFT);

                    $this->set('votes', $voteKey, [
                        'id'           => $voteKey,
                        'voter_id'     => $voterId,
                        'candidate_id' => $chosenCandidate,
                        'election_id'  => $config['election_id'],
                        'position'     => $position,
                        'created_at'   => $this->randomDateBetween($config['start'], $config['end']),
                    ]);

                    $voteCounter++;
                }
            }

            $totalVoteRows = $config['voter_count'] * $positionCount;
            $this->logSuccess("{$config['election_id']}: {$config['voter_count']} voters — {$totalVoteRows} vote rows written.");
        }
    }

    private function seedReports(): void
    {
        $this->logInfo('Seeding /reports...');

        $reports = [
            'report_001' => [
                'id'              => 'report_001',
                'election_id'     => 'election_001',
                'generated_by'    => 'ADMaB3kL9mNpQr',
                'report_type'     => 'vote_summary',
                'status'          => 'completed',
                'file_path'       => 'reports/election_001/vote_summary_2024.pdf',
                'file_name'       => 'vote_summary_2024.pdf',
                'file_format'     => 'pdf',
                'file_size_bytes' => 204800,
                'filters'         => null,
                'summary'         => ['total_votes' => 1200, 'total_voters' => 300, 'turnout_percent' => 100.0, 'positions' => ['President', 'Vice President', 'Secretary', 'Treasurer']],
                'error_message'   => null,
                'created_at'      => $this->daysAgo(2),
                'updated_at'      => $this->daysAgo(2),
            ],
            'report_002' => [
                'id'              => 'report_002',
                'election_id'     => 'election_001',
                'generated_by'    => 'SAOxK7wP2dYcHj',
                'report_type'     => 'candidate_results',
                'status'          => 'completed',
                'file_path'       => 'reports/election_001/candidate_results_2024.xlsx',
                'file_name'       => 'candidate_results_2024.xlsx',
                'file_format'     => 'xlsx',
                'file_size_bytes' => 51200,
                'filters'         => ['position' => 'President'],
                'summary'         => ['winner' => 'cand_001', 'winner_name' => 'Diana Lim', 'runner_up' => 'cand_002'],
                'error_message'   => null,
                'created_at'      => $this->daysAgo(2),
                'updated_at'      => $this->daysAgo(2),
            ],
            'report_003' => [
                'id'              => 'report_003',
                'election_id'     => 'election_003',
                'generated_by'    => 'ADMaB3kL9mNpQr',
                'report_type'     => 'voter_turnout',
                'status'          => 'pending',       // report not yet generated — election still running
                'file_path'       => null,
                'file_name'       => null,
                'file_format'     => null,
                'file_size_bytes' => null,
                'filters'         => null,
                'summary'         => [
                    'total_votes'     => $this->activeVoterCount * 2,                                                    // 2 positions per voter
                    'total_voters'    => $this->activeVoterCount,                                                        // 60% of students so far
                    'turnout_percent' => round(($this->activeVoterCount / $this->totalStudentCount) * 100, 2),           // always < 100%
                    'positions'       => ['Governor', 'Vice Governor'],
                ],
                'error_message'   => null,
                'created_at'      => $this->now(),
                'updated_at'      => $this->now(),
            ],
            'report_004' => [
                'id'              => 'report_004',
                'election_id'     => 'election_001',
                'generated_by'    => 'SAOxK7wP2dYcHj',
                'report_type'     => 'party_results',
                'status'          => 'failed',
                'file_path'       => null,
                'file_name'       => null,
                'file_format'     => null,
                'file_size_bytes' => null,
                'filters'         => null,
                'summary'         => null,
                'error_message'   => 'Timeout: PDF generation service unavailable.',
                'created_at'      => $this->daysAgo(1),
                'updated_at'      => $this->daysAgo(1),
            ],
        ];

        foreach ($reports as $key => $report) {
            if ($report['status'] === 'failed') {
                $this->logWarning("Report {$key} has status 'failed' — seeding with error state.");
            }
            $this->set('reports', $key, $report);
        }

        $this->logSuccess('Reports seeded successfully.');
    }

    private function seedSystemLogs(): void
    {
        $this->logInfo('Seeding /system_logs...');

        $logs = [
            'log_001' => ['id' => 'log_001', 'level' => 'info',     'message' => "Election 'SSC General Elections 2024' status changed to closed.",      'context' => ['election_id' => 'election_001', 'changed_by' => 'ADMaB3kL9mNpQr'],                            'created_at' => $this->daysAgo(3)],
            'log_002' => ['id' => 'log_002', 'level' => 'info',     'message' => 'User STUrJ6hD1fXbLn registered as a candidate for position President.', 'context' => ['user_id' => 'STUrJ6hD1fXbLn', 'election_id' => 'election_001'],                              'created_at' => $this->daysAgo(25)],
            'log_003' => ['id' => 'log_003', 'level' => 'warning',  'message' => 'Duplicate vote attempt detected for voter STUyT9nB7rVdQz.',              'context' => ['voter_id' => 'STUyT9nB7rVdQz', 'election_id' => 'election_001', 'position' => 'President'], 'created_at' => $this->daysAgo(8)],
            'log_004' => ['id' => 'log_004', 'level' => 'error',    'message' => 'Report generation failed for report_004.',                               'context' => ['report_id' => 'report_004', 'reason' => 'PDF service timeout'],                             'created_at' => $this->daysAgo(1)],
            'log_005' => ['id' => 'log_005', 'level' => 'critical', 'message' => 'Multiple failed login attempts detected from IP 192.168.1.50.',          'context' => ['ip' => '192.168.1.50', 'attempts' => 10],                                                   'created_at' => $this->now()],
        ];

        foreach ($logs as $key => $log) {
            match ($log['level']) {
                'warning'           => $this->logWarning("System log {$key}: {$log['message']}"),
                'error', 'critical' => $this->logError("System log {$key}: {$log['message']}"),
                default             => $this->logInfo("System log {$key}: {$log['message']}"),
            };

            $this->set('system_logs', $key, $log);
        }

        $this->logSuccess('System logs seeded successfully.');
    }
}
