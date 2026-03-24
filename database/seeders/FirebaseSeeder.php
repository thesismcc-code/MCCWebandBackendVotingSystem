<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class FirebaseSeeder extends Seeder
{
    protected Database $db;

    // ─── INIT ────────────────────────────────────────────────────────────────────
    // NOTE: Do NOT use __construct() for Firebase init — Laravel resolves seeders
    // before .env is fully booted in some versions. Init inside run() instead.

    private function initFirebase(): void
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');
        $databaseUrl     = env('FIREBASE_DATABASE_URL');

        if (! $credentialsPath) {
            throw new \RuntimeException(
                "FIREBASE_CREDENTIALS is not set in your .env file.\n" .
                "Example: FIREBASE_CREDENTIALS=storage/app/firebase/serviceAccountKey.json"
            );
        }

        if (! $databaseUrl) {
            throw new \RuntimeException(
                "FIREBASE_DATABASE_URL is not set in your .env file.\n" .
                "Example: FIREBASE_DATABASE_URL=https://your-project-id-default-rtdb.firebaseio.com"
            );
        }

        $absolutePath = base_path($credentialsPath);

        if (! file_exists($absolutePath)) {
            throw new \RuntimeException(
                "Firebase credentials file not found at: {$absolutePath}\n" .
                "Make sure you placed serviceAccountKey.json at: {$credentialsPath}"
            );
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

        $this->command->info("\n🔥  Seeding Firebase Realtime Database...\n");

        $this->seedUsers();
        $this->seedElections();
        $this->seedPartyLists();
        $this->seedCandidates();
        $this->seedVotes();
        $this->seedReports();
        $this->seedSystemLogs();

        $this->command->info("\n🎉  Firebase seeding complete!\n");
    }

    // ─── HELPERS ────────────────────────────────────────────────────────────────

    private function set(string $collection, string $key, array $data): void
    {
        $this->db->getReference("/{$collection}/{$key}")->set($data);
        $this->command->line("  ✅  /{$collection}/{$key}");
    }

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

    // ─── COLLECTIONS ────────────────────────────────────────────────────────────

    private function seedUsers(): void
    {
        $this->command->line('📦  Seeding /users...');

        $password = Hash::make('password123');

        // IDs follow generateID() format: {PREFIX}{12 random chars}
        // student_id follows generateStudentID() format: STU-{YYY}-{seq}
        // teacher_id follows generateID() format: THR{12 random chars}
        // Firebase node key = the role-based ID itself (not user_00X)
        $yearSuffix = substr(date('Y'), 1); // e.g. "026" for 2026

        $users = [
            'ADMaB3kL9mNpQr' => ['first_name' => 'Alice',   'middle_name' => 'Marie',  'last_name' => 'Santos',     'email' => 'alice.santos@school.edu',      'role' => 'admin',   'student_id' => null,                        'teacher_id' => null],
            'SAOxK7wP2dYcHj' => ['first_name' => 'Bob',     'middle_name' => 'Cruz',   'last_name' => 'Reyes',      'email' => 'bob.reyes@school.edu',         'role' => 'sao',     'student_id' => null,                        'teacher_id' => null],
            'THRmN4vZ8qEtWs' => ['first_name' => 'Carlos',  'middle_name' => 'Jose',   'last_name' => 'Dela Cruz',  'email' => 'carlos.delacruz@school.edu',   'role' => 'teacher', 'student_id' => null,                        'teacher_id' => 'THRmN4vZ8qEtWs'],
            'STUrJ6hD1fXbLn' => ['first_name' => 'Diana',   'middle_name' => 'Grace',  'last_name' => 'Lim',        'email' => 'diana.lim@school.edu',         'role' => 'student', 'student_id' => "STU-{$yearSuffix}-001",    'teacher_id' => null],
            'STUgC5pM3kWoAe' => ['first_name' => 'Eduardo', 'middle_name' => 'Ramon',  'last_name' => 'Tan',        'email' => 'eduardo.tan@school.edu',       'role' => 'student', 'student_id' => "STU-{$yearSuffix}-002",    'teacher_id' => null],
            'STUyT9nB7rVdQz' => ['first_name' => 'Faye',    'middle_name' => 'Hope',   'last_name' => 'Garcia',     'email' => 'faye.garcia@school.edu',       'role' => 'student', 'student_id' => "STU-{$yearSuffix}-003",    'teacher_id' => null],
            'STUhR2mK4sJuPf' => ['first_name' => 'George',  'middle_name' => 'Paul',   'last_name' => 'Mendoza',    'email' => 'george.mendoza@school.edu',    'role' => 'student', 'student_id' => "STU-{$yearSuffix}-004",    'teacher_id' => null],
            'STUwL8cF6tNxEv' => ['first_name' => 'Hannah',  'middle_name' => 'Joy',    'last_name' => 'Villanueva', 'email' => 'hannah.villanueva@school.edu', 'role' => 'student', 'student_id' => "STU-{$yearSuffix}-005",    'teacher_id' => null],
        ];

        foreach ($users as $key => $user) {
            $this->set('users', $key, array_merge($user, [
                'id'                => $key,
                'password'          => $password,
                'email_verified_at' => $this->now(),
                'remember_token'    => null,
                'created_at'        => $this->now(),
                'updated_at'        => $this->now(),
            ]));
        }
    }

    private function seedElections(): void
    {
        $this->command->line('📦  Seeding /elections...');

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
    }

    private function seedPartyLists(): void
    {
        $this->command->line('📦  Seeding /party_lists...');

        $parties = [
            'party_001' => ['id' => 'party_001', 'party_name' => 'Unity Party',       'description' => 'Committed to unity and inclusive student governance.',          'logo' => 'logos/unity_party.png',       'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
            'party_002' => ['id' => 'party_002', 'party_name' => 'Progreso Alliance',  'description' => 'Advocates for academic excellence and student welfare.',         'logo' => 'logos/progreso_alliance.png', 'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
            'party_003' => ['id' => 'party_003', 'party_name' => 'Independent',        'description' => 'Non-partisan independent candidates.',                           'logo' => null,                          'created_at' => $this->daysAgo(60), 'updated_at' => $this->daysAgo(60)],
        ];

        foreach ($parties as $key => $party) {
            $this->set('party_lists', $key, $party);
        }
    }

    private function seedCandidates(): void
    {
        $this->command->line('📦  Seeding /candidates...');

        $candidates = [
            'cand_001' => ['id' => 'cand_001', 'user_id' => 'STUrJ6hD1fXbLn', 'party_list_id' => 'party_001', 'election_id' => 'election_001', 'position' => 'President',      'manifesto' => 'I will champion transparency and student welfare in all SSC decisions.', 'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_002' => ['id' => 'cand_002', 'user_id' => 'STUgC5pM3kWoAe', 'party_list_id' => 'party_002', 'election_id' => 'election_001', 'position' => 'President',      'manifesto' => 'Progress through unity — a stronger voice for every student.',          'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_003' => ['id' => 'cand_003', 'user_id' => 'STUyT9nB7rVdQz', 'party_list_id' => 'party_001', 'election_id' => 'election_001', 'position' => 'Vice President', 'manifesto' => 'Bridging the gap between students and the administration.',               'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_004' => ['id' => 'cand_004', 'user_id' => 'STUhR2mK4sJuPf', 'party_list_id' => 'party_003', 'election_id' => 'election_001', 'position' => 'Vice President', 'manifesto' => 'An independent voice dedicated solely to student needs.',                  'status' => 'approved', 'created_at' => $this->daysAgo(25), 'updated_at' => $this->daysAgo(20)],
            'cand_005' => ['id' => 'cand_005', 'user_id' => 'STUwL8cF6tNxEv', 'party_list_id' => 'party_002', 'election_id' => 'election_003', 'position' => 'Governor',       'manifesto' => 'Engineering a better tomorrow for all CoE students.',                   'status' => 'pending',  'created_at' => $this->daysAgo(5),  'updated_at' => $this->daysAgo(5)],
        ];

        foreach ($candidates as $key => $candidate) {
            $this->set('candidates', $key, $candidate);
        }
    }

    private function seedVotes(): void
    {
        $this->command->line('📦  Seeding /votes...');

        $votes = [
            'vote_001' => ['id' => 'vote_001', 'voter_id' => 'STUyT9nB7rVdQz', 'candidate_id' => 'cand_001', 'election_id' => 'election_001', 'position' => 'President',      'created_at' => $this->daysAgo(8)],
            'vote_002' => ['id' => 'vote_002', 'voter_id' => 'STUyT9nB7rVdQz', 'candidate_id' => 'cand_003', 'election_id' => 'election_001', 'position' => 'Vice President', 'created_at' => $this->daysAgo(8)],
            'vote_003' => ['id' => 'vote_003', 'voter_id' => 'STUhR2mK4sJuPf', 'candidate_id' => 'cand_002', 'election_id' => 'election_001', 'position' => 'President',      'created_at' => $this->daysAgo(7)],
            'vote_004' => ['id' => 'vote_004', 'voter_id' => 'STUhR2mK4sJuPf', 'candidate_id' => 'cand_003', 'election_id' => 'election_001', 'position' => 'Vice President', 'created_at' => $this->daysAgo(7)],
            'vote_005' => ['id' => 'vote_005', 'voter_id' => 'STUwL8cF6tNxEv', 'candidate_id' => 'cand_001', 'election_id' => 'election_001', 'position' => 'President',      'created_at' => $this->daysAgo(6)],
            'vote_006' => ['id' => 'vote_006', 'voter_id' => 'STUwL8cF6tNxEv', 'candidate_id' => 'cand_004', 'election_id' => 'election_001', 'position' => 'Vice President', 'created_at' => $this->daysAgo(6)],
        ];

        foreach ($votes as $key => $vote) {
            $this->set('votes', $key, $vote);
        }
    }

    private function seedReports(): void
    {
        $this->command->line('📦  Seeding /reports...');

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
                'summary'         => ['total_votes' => 6, 'total_voters' => 3, 'turnout_percent' => 60.0, 'positions' => ['President', 'Vice President']],
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
                'summary'         => ['winner' => 'cand_001', 'winner_name' => 'Diana Lim', 'votes_received' => 2, 'runner_up' => 'cand_002'],
                'error_message'   => null,
                'created_at'      => $this->daysAgo(2),
                'updated_at'      => $this->daysAgo(2),
            ],
            'report_003' => [
                'id'              => 'report_003',
                'election_id'     => 'election_003',
                'generated_by'    => 'ADMaB3kL9mNpQr',
                'report_type'     => 'voter_turnout',
                'status'          => 'pending',
                'file_path'       => null,
                'file_name'       => null,
                'file_format'     => null,
                'file_size_bytes' => null,
                'filters'         => null,
                'summary'         => null,
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
            $this->set('reports', $key, $report);
        }
    }

    private function seedSystemLogs(): void
    {
        $this->command->line('📦  Seeding /system_logs...');

        $logs = [
            'log_001' => ['id' => 'log_001', 'level' => 'info',     'message' => "Election 'SSC General Elections 2024' status changed to closed.",     'context' => ['election_id' => 'election_001', 'changed_by' => 'ADMaB3kL9mNpQr'],                              'created_at' => $this->daysAgo(3)],
            'log_002' => ['id' => 'log_002', 'level' => 'info',     'message' => 'User STUrJ6hD1fXbLn registered as a candidate for position President.', 'context' => ['user_id' => 'STUrJ6hD1fXbLn', 'election_id' => 'election_001'],                                'created_at' => $this->daysAgo(25)],
            'log_003' => ['id' => 'log_003', 'level' => 'warning',  'message' => 'Duplicate vote attempt detected for voter STUyT9nB7rVdQz.',             'context' => ['voter_id' => 'STUyT9nB7rVdQz', 'election_id' => 'election_001', 'position' => 'President'],   'created_at' => $this->daysAgo(8)],
            'log_004' => ['id' => 'log_004', 'level' => 'error',    'message' => 'Report generation failed for report_004.',                              'context' => ['report_id' => 'report_004', 'reason' => 'PDF service timeout'],                               'created_at' => $this->daysAgo(1)],
            'log_005' => ['id' => 'log_005', 'level' => 'critical', 'message' => 'Multiple failed login attempts detected from IP 192.168.1.50.',         'context' => ['ip' => '192.168.1.50', 'attempts' => 10],                                                     'created_at' => $this->now()],
        ];

        foreach ($logs as $key => $log) {
            $this->set('system_logs', $key, $log);
        }
    }
}
