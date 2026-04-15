<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Factory;

class FirebaseSeeder extends Seeder
{
    protected Database $db;

    /** Computed in seedVotes() and reused in seedReports() for consistent turnout stats. */
    private int $activeVoterCount = 0;

    private int $totalStudentCount = 0;

    // ─── LOGGING HELPERS ────────────────────────────────────────────────────────

    private function logSuccess(string $message): void
    {
        $this->command->line("  <fg=green>{$message}</>");
        Log::info($message);
    }

    private function logWarning(string $message): void
    {
        $this->command->line("  <fg=yellow>{$message}</>");
        Log::warning($message);
    }

    private function logError(string $message): void
    {
        $this->command->line("  <fg=red>{$message}</>");
        Log::error($message);
    }

    private function logInfo(string $message): void
    {
        $this->command->line("  {$message}");
        Log::info($message);
    }

    // ─── FIREBASE INIT ──────────────────────────────────────────────────────────

    private function initFirebase(): void
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');
        $databaseUrl = env('FIREBASE_DATABASE_URL');

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
        $this->seedPositions();
        $this->seedPartyLists();
        $this->seedCandidates();
        $this->seedVotes();
        $this->seedReports();
        $this->seedSystemActivity();
        $this->seedSecurityLogs();

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
        $endTs = strtotime($end);

        return date('Y-m-d\TH:i:s', rand($startTs, $endTs));
    }

    /**
     * Generate a student ID encoding the enrollment year and a sequential number.
     * Format: STU-{enrollYear}-{seq}
     * Examples: STU-2023-001, STU-2026-100
     *
     * Enrollment years map to year levels (as of the current year):
     *   currentYear - 0 → 1st Year (enrolled this year)
     *   currentYear - 1 → 2nd Year
     *   currentYear - 2 → 3rd Year
     *   currentYear - 3 → 4th Year
     */
    private function generateStudentId(int $enrollYear, int $seq): string
    {
        $shortYear = substr((string) $enrollYear, -3);

        return 'STU-'.$shortYear.'-'.str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    private function generateUserId(string $role): string
    {
        $prefix = match ($role) {
            'admin' => 'ADM',
            'student' => 'STU',
            'teacher' => 'THR',
            'sao' => 'SAO',
            'comelec' => 'CML',
            default => 'USR',
        };

        return $prefix.Str::random(12);
    }

    // ─── COLLECTIONS ────────────────────────────────────────────────────────────

    private function seedUsers(): void
    {
        $this->logInfo('Seeding /users...');

        $studentPassword = Hash::make('password123');

        $courses = [
            'Computer Science',
            'Information Technology',
            'Business Administration',
            'Civil Engineering',
            'Electrical Engineering',
        ];

        $yearLevelLabels = [
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
        ];

        $staff = [
            'ADM8N2K4Q6W0X1' => [
                'first_name' => 'System', 'middle_name' => '', 'last_name' => 'Administrator',
                'email' => 'admin@thsismcc.com', 'role' => 'admin', 'password' => Hash::make('admin123'),
                'student_id' => null, 'admin_id' => 'ADM8N2K4Q6W0X1',
            ],
            'CML7M3R5T9Y2Z4' => [
                'first_name' => 'Paul', 'middle_name' => '', 'last_name' => 'Quizon',
                'email' => 'paulquizon@thesismcc.com', 'role' => 'comelec', 'password' => Hash::make('paul123'),
                'student_id' => null, 'admin_id' => null,
            ],
            'SAO5P1L8V3B7N6' => [
                'first_name' => 'Student', 'middle_name' => 'Affairs', 'last_name' => 'Officer',
                'email' => 'sao@thesismcc.com', 'role' => 'sao', 'password' => Hash::make('saol123'),
                'student_id' => null, 'admin_id' => null,
            ],
        ];

        foreach ($staff as $key => $user) {
            $password = $user['password'];
            unset($user['password']);
            $this->set('users', $key, array_merge($user, [
                'id' => $key,
                'password' => $password,
                'course' => null,
                'year_level' => null,
                'comelec_id' => null,
                'email_verified_at' => $this->now(),
                'remember_token' => null,
                'is_deleted' => false,
                'created_at' => $this->daysAgo(90),
                'updated_at' => $this->daysAgo(90),
            ]));
        }

        $currentYear = (int) date('Y');
        $enrollYears = [
            $currentYear - 3,
            $currentYear - 2,
            $currentYear - 1,
            $currentYear,
        ];

        $fixedStudentSeed = [
            'STU-023-001' => ['first_name' => 'Diana', 'middle_name' => 'Marie', 'last_name' => 'Lim', 'enroll_year_index' => 0, 'seq' => 1, 'course' => 'Computer Science'],
            'STU-024-001' => ['first_name' => 'Miguel', 'middle_name' => 'Santos', 'last_name' => 'Villanueva', 'enroll_year_index' => 1, 'seq' => 1, 'course' => 'Information Technology'],
            'STU-025-001' => ['first_name' => 'Rosa', 'middle_name' => 'Cruz', 'last_name' => 'Castro', 'enroll_year_index' => 2, 'seq' => 1, 'course' => 'Business Administration'],
            'STU-026-001' => ['first_name' => 'Carlos', 'middle_name' => 'Reyes', 'last_name' => 'Bautista', 'enroll_year_index' => 3, 'seq' => 1, 'course' => 'Civil Engineering'],
            'STU-023-002' => ['first_name' => 'Ana', 'middle_name' => 'Lopez', 'last_name' => 'Ramos', 'enroll_year_index' => 0, 'seq' => 2, 'course' => 'Electrical Engineering'],
        ];

        $emailCounter = 1;

        foreach ($fixedStudentSeed as $userId => $meta) {
            $enrollYear = $enrollYears[$meta['enroll_year_index']];
            $yearLevel = $currentYear - $enrollYear + 1;
            $yearLevelLabel = $yearLevelLabels[$yearLevel] ?? "{$yearLevel}th Year";
            $daysOld = ($currentYear - $enrollYear) * 365 + rand(0, 60);

            $this->set('users', $userId, [
                'id' => $userId,
                'first_name' => $meta['first_name'],
                'middle_name' => $meta['middle_name'],
                'last_name' => $meta['last_name'],
                'email' => 'student'.str_pad((string) $emailCounter, 4, '0', STR_PAD_LEFT).'@school.edu',
                'password' => $studentPassword,
                'role' => 'student',
                'student_id' => $this->generateStudentId($enrollYear, $meta['seq']),
                'course' => $meta['course'],
                'year_level' => $yearLevelLabel,
                'admin_id' => null,
                'comelec_id' => null,
                'email_verified_at' => $this->daysAgo($daysOld),
                'remember_token' => null,
                'is_deleted' => false,
                'created_at' => $this->daysAgo($daysOld),
                'updated_at' => $this->daysAgo(rand(1, 30)),
            ]);

            $emailCounter++;
        }

        $this->logSuccess('Five deterministic candidate student accounts seeded (fixed Firebase user IDs).');

        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Miguel', 'Rosa', 'Carlos', 'Luz', 'Ramon', 'Elena', 'Pedro', 'Sofia', 'Luis', 'Carmen', 'Antonio', 'Isabel', 'Diego', 'Patricia', 'Eduardo', 'Monica', 'Felix', 'Jasmine', 'Cedric', 'Janine', 'Ryan', 'Aileen', 'Mark', 'Christine', 'Kevin', 'Trisha'];
        $middleNames = ['Santos', 'Reyes', 'Cruz', 'Dela Cruz', 'Garcia', 'Mendoza', 'Lopez', 'Torres', 'Hernandez', 'Flores'];
        $lastNames = ['Bautista', 'Villanueva', 'Ramos', 'Castro', 'Aquino', 'Gonzales', 'Diaz', 'Marquez', 'Quispe', 'Lim', 'Tan', 'Go', 'Chan', 'Uy', 'Chua', 'Sy', 'Ko', 'Ng', 'Yu', 'Dee'];

        foreach ($enrollYears as $enrollYearIndex => $enrollYear) {
            $yearLevel = $currentYear - $enrollYear + 1;
            $yearLevelLabel = $yearLevelLabels[$yearLevel] ?? "{$yearLevel}th Year";

            $countThisYear = 99;
            if ($enrollYearIndex === 3) {
                $countThisYear = 98;
            }

            $this->logInfo("Generating {$countThisYear} students enrolled in {$enrollYear} ({$yearLevelLabel})...");

            $seqOffset = match ($enrollYearIndex) {
                0 => 2,
                default => 1,
            };

            for ($seq = 1; $seq <= $countThisYear; $seq++) {
                $userId = $this->generateStudentId($enrollYear, $seq + $seqOffset);
                $daysOld = ($currentYear - $enrollYear) * 365 + rand(0, 60);

                $this->set('users', $userId, [
                    'id' => $userId,
                    'first_name' => $firstNames[array_rand($firstNames)],
                    'middle_name' => $middleNames[array_rand($middleNames)],
                    'last_name' => $lastNames[array_rand($lastNames)],
                    'email' => 'student'.str_pad((string) $emailCounter, 4, '0', STR_PAD_LEFT).'@school.edu',
                    'password' => $studentPassword,
                    'role' => 'student',
                    'student_id' => $this->generateStudentId($enrollYear, $seq + $seqOffset),
                    'course' => $courses[array_rand($courses)],
                    'year_level' => $yearLevelLabel,
                    'admin_id' => null,
                    'comelec_id' => null,
                    'email_verified_at' => $this->daysAgo($daysOld),
                    'remember_token' => null,
                    'is_deleted' => false,
                    'created_at' => $this->daysAgo($daysOld),
                    'updated_at' => $this->daysAgo(rand(1, 30)),
                ]);

                $emailCounter++;
            }

            $this->logSuccess("Enrollment year {$enrollYear} ({$yearLevelLabel}): {$countThisYear} students seeded.");
        }

        $this->logSuccess('395 additional students seeded (99 + 99 + 99 + 98). Total students: 400 with 5 fixed candidate-linked accounts.');
    }

    private function seedElections(): void
    {
        $this->logInfo('Seeding /elections...');

        $elections = [
            'election_001' => [
                'id' => 'election_001',
                'election_name' => 'SSC General Elections 2024',
                'description' => 'Annual Supreme Student Council election open to all enrolled students.',
                'semester' => '2nd Semester',
                'academic_year' => '2023-2024',
                'start_date' => $this->daysAgo(10),
                'end_date' => $this->daysAgo(3),
                'opening_time' => '08:00 AM',
                'closing_time' => '05:00 PM',
                'status' => 'closed',
                'created_at' => $this->daysAgo(30),
                'updated_at' => $this->daysAgo(3),
            ],
            'election_002' => [
                'id' => 'election_002',
                'election_name' => 'SSC By-Elections 2025',
                'description' => 'By-election for vacated SSC positions.',
                'semester' => '1st Semester',
                'academic_year' => '2025-2026',
                'start_date' => $this->daysFromNow(5),
                'end_date' => $this->daysFromNow(7),
                'opening_time' => '08:00 AM',
                'closing_time' => '05:00 PM',
                'status' => 'upcoming',
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            'election_003' => [
                'id' => 'election_003',
                'election_name' => 'College of Engineering Elections 2025',
                'description' => 'Department-level student council election for CoE students.',
                'semester' => '2nd Semester',
                'academic_year' => '2024-2025',
                'start_date' => $this->daysAgo(1),
                'end_date' => $this->daysFromNow(1),
                'opening_time' => '09:00 AM',
                'closing_time' => '06:00 PM',
                'status' => 'active',
                'created_at' => $this->daysAgo(20),
                'updated_at' => $this->daysAgo(1),
            ],
        ];

        foreach ($elections as $key => $election) {
            $this->set('elections', $key, $election);
        }

        $this->logSuccess('Elections seeded successfully.');
    }

    private function seedPositions(): void
    {
        $this->logInfo('Seeding /positions...');

        $positions = [
            'pos_e001_president' => [
                'id' => 'pos_e001_president',
                'election_id' => 'election_001',
                'position_name' => 'President',
                'max_votes' => 1,
                'created_at' => $this->daysAgo(28),
                'updated_at' => $this->daysAgo(20),
            ],
            'pos_e001_vice_president' => [
                'id' => 'pos_e001_vice_president',
                'election_id' => 'election_001',
                'position_name' => 'Vice President',
                'max_votes' => 1,
                'created_at' => $this->daysAgo(28),
                'updated_at' => $this->daysAgo(20),
            ],
            'pos_e001_senators' => [
                'id' => 'pos_e001_senators',
                'election_id' => 'election_001',
                'position_name' => 'Senators',
                'max_votes' => 9,
                'created_at' => $this->daysAgo(28),
                'updated_at' => $this->daysAgo(20),
            ],
            'pos_e002_president' => [
                'id' => 'pos_e002_president',
                'election_id' => 'election_002',
                'position_name' => 'President',
                'max_votes' => 1,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            'pos_e002_vice_president' => [
                'id' => 'pos_e002_vice_president',
                'election_id' => 'election_002',
                'position_name' => 'Vice President',
                'max_votes' => 1,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            'pos_e002_senators' => [
                'id' => 'pos_e002_senators',
                'election_id' => 'election_002',
                'position_name' => 'Senators',
                'max_votes' => 9,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            'pos_e003_president' => [
                'id' => 'pos_e003_president',
                'election_id' => 'election_003',
                'position_name' => 'President',
                'max_votes' => 1,
                'created_at' => $this->daysAgo(18),
                'updated_at' => $this->daysAgo(10),
            ],
            'pos_e003_vice_president' => [
                'id' => 'pos_e003_vice_president',
                'election_id' => 'election_003',
                'position_name' => 'Vice President',
                'max_votes' => 1,
                'created_at' => $this->daysAgo(18),
                'updated_at' => $this->daysAgo(10),
            ],
            'pos_e003_senators' => [
                'id' => 'pos_e003_senators',
                'election_id' => 'election_003',
                'position_name' => 'Senators',
                'max_votes' => 9,
                'created_at' => $this->daysAgo(18),
                'updated_at' => $this->daysAgo(10),
            ],
        ];

        foreach ($positions as $key => $position) {
            $this->set('positions', $key, $position);
        }

        $this->logSuccess('Positions seeded successfully.');
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

        $party = [
            'party_001' => 'Unity Party',
            'party_002' => 'Progreso Alliance',
            'party_003' => 'Independent',
        ];

        $e1 = [
            'President' => 'pos_e001_president',
            'Vice President' => 'pos_e001_vice_president',
            'Senators' => 'pos_e001_senators',
        ];

        $e3 = [
            'President' => 'pos_e003_president',
            'Vice President' => 'pos_e003_vice_president',
            'Senators' => 'pos_e003_senators',
        ];

        $rows = [
            ['cand_001', 'STU-023-001', 'party_001', 'election_001', 'President',      'Transparency and student welfare in all SSC decisions.',       $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_002', 'STU-024-001', 'party_002', 'election_001', 'President',      'Progress through unity — a stronger voice for every student.',  $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_003', 'STU-025-001', 'party_001', 'election_001', 'Vice President', 'Bridging the gap between students and administration.',         $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_004', 'STU-026-001', 'party_003', 'election_001', 'Vice President', 'An independent voice dedicated solely to student needs.',       $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_005', 'STU-023-002', 'party_002', 'election_001', 'Senators',      'Organized, transparent, and accountable to every student.',    $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_006', 'ADM8N2K4Q6W0X1', 'party_001', 'election_001', 'Senators',      'Fiscal responsibility and full financial transparency.',         $this->daysAgo(25), $this->daysAgo(20), $e1],
            ['cand_007', 'SAO5P1L8V3B7N6', 'party_001', 'election_003', 'President',      'Engineering a better tomorrow through transparent leadership.',   $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_008', 'CML7M3R5T9Y2Z4', 'party_002', 'election_003', 'President',      'Stronger students, stronger CoE — innovation in governance.',    $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_009', 'STU-023-001', 'party_003', 'election_003', 'President',      'Independent and student-first — CoE deserves real leadership.',   $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_010', 'STU-024-001', 'party_001', 'election_003', 'Vice President', 'Bridging CoE students to real academic and industry support.',    $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_011', 'STU-025-001', 'party_002', 'election_003', 'Vice President', 'Student wellness and academic support for every engineer.',        $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_012', 'STU-026-001', 'party_003', 'election_003', 'Vice President', 'An independent advocate for every CoE student concern.',          $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_013', 'STU-023-002', 'party_001', 'election_003', 'Senators',      'Efficient records, clear communication, full accountability.',     $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_014', 'ADM8N2K4Q6W0X1', 'party_002', 'election_003', 'Senators',      'Every CoE decision documented, every student voice recorded.',     $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_015', 'SAO5P1L8V3B7N6', 'party_003', 'election_003', 'Senators',      'Transparent records and open communication as core values.',       $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_016', 'CML7M3R5T9Y2Z4', 'party_002', 'election_003', 'Senators',      'Responsible stewardship of every peso entrusted by CoE students.', $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_017', 'STU-023-001', 'party_001', 'election_003', 'Senators',      'Full financial transparency and zero tolerance for misuse.',        $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_018', 'STU-024-001', 'party_003', 'election_003', 'Senators',      'Independent oversight ensuring every fund goes to students.',       $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_019', 'STU-025-001', 'party_001', 'election_003', 'Senators',        'Rigorous auditing to ensure every fund is properly accounted for.', $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_020', 'STU-026-001', 'party_002', 'election_003', 'Senators',        'Checks and balances — protecting the integrity of CoE funds.',      $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_021', 'STU-023-002', 'party_003', 'election_003', 'Senators',        'Independent auditing with zero conflict of interest.',              $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_022', 'ADM8N2K4Q6W0X1', 'party_001', 'election_003', 'Senators',            'Amplifying the CoE student voice on every platform.',              $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_023', 'SAO5P1L8V3B7N6', 'party_002', 'election_003', 'Senators',            'Creative, consistent, and student-centered communications.',        $this->daysAgo(15), $this->daysAgo(10), $e3],
            ['cand_024', 'CML7M3R5T9Y2Z4', 'party_003', 'election_003', 'Senators',            'Independent voice ensuring all CoE news reaches every student.',    $this->daysAgo(15), $this->daysAgo(10), $e3],
        ];

        $usersSnapshot = $this->db->getReference('/users')->getSnapshot();
        $usersById = is_array($usersSnapshot->getValue()) ? $usersSnapshot->getValue() : [];

        $candidateUserIds = array_values(array_unique(array_map(
            static fn (array $row): string => $row[1],
            $rows
        )));

        foreach ($candidateUserIds as $candidateUserId) {
            if (isset($usersById[$candidateUserId]) && is_array($usersById[$candidateUserId])) {
                continue;
            }

            $fallbackRole = str_starts_with($candidateUserId, 'ADM')
                ? 'admin'
                : (str_starts_with($candidateUserId, 'SAO')
                    ? 'sao'
                    : (str_starts_with($candidateUserId, 'CML') || str_starts_with($candidateUserId, 'THR')
                        ? 'comelec'
                        : 'student'));

            $fallback = [
                'id' => $candidateUserId,
                'first_name' => 'Seeded',
                'middle_name' => '',
                'last_name' => 'User',
                'email' => strtolower($candidateUserId).'@school.edu',
                'password' => Hash::make('password123'),
                'role' => $fallbackRole,
                'student_id' => $fallbackRole === 'student' ? $candidateUserId : null,
                'course' => null,
                'year_level' => null,
                'admin_id' => null,
                'comelec_id' => null,
                'email_verified_at' => $this->now(),
                'remember_token' => null,
                'is_deleted' => false,
                'created_at' => $this->daysAgo(30),
                'updated_at' => $this->daysAgo(1),
            ];

            $this->set('users', $candidateUserId, $fallback);
            $usersById[$candidateUserId] = $fallback;
            $this->logWarning("Missing candidate user {$candidateUserId} was auto-seeded to avoid unknown profile mapping.");
        }

        foreach ($rows as $row) {
            [$id, $userId, $partyId, $electionId, $positionName, $platform, $createdAt, $updatedAt, $posMap] = $row;
            $positionId = $posMap[$positionName];
            $user = $usersById[$userId] ?? [];
            $fullName = trim((string) (($user['first_name'] ?? '').' '.($user['middle_name'] ?? '').' '.($user['last_name'] ?? '')));
            if ($fullName === '') {
                $fullName = 'Seeded User '.$userId;
            }
            $course = (string) ($user['course'] ?? '');
            $year = (string) ($user['year_level'] ?? '');

            $this->set('candidates', $id, [
                'id' => $id,
                'user_id' => $userId,
                'party_list_id' => $partyId,
                'election_id' => $electionId,
                'position' => $positionName,
                'position_id' => $positionId,
                'position_name' => $positionName,
                'full_name' => $fullName,
                'course' => $course,
                'year' => $year,
                'year_level' => $year,
                'political_party' => $party[$partyId],
                'platform_agenda' => $platform,
                'manifesto' => $platform,
                'image_url' => '',
                'status' => 'approved',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
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
        $totalStudents = count($allStudentIds);
        $this->totalStudentCount = $totalStudents;
        $this->logInfo("Found {$totalStudents} student(s) to seed votes for.");

        $election001Config = [
            'election_id' => 'election_001',
            'voter_count' => min(400, $totalStudents),
            'start' => $this->daysAgo(10),
            'end' => $this->daysAgo(3),
            'positions' => [
                'President' => ['cand_001', 'cand_002'],
                'Vice President' => ['cand_003', 'cand_004'],
                'Senators' => ['cand_005', 'cand_006'],
            ],
        ];

        $activeVoterCount = (int) floor($totalStudents * 0.60);
        $this->activeVoterCount = $activeVoterCount;

        $election003Config = [
            'election_id' => 'election_003',
            'voter_count' => $activeVoterCount,
            'start' => $this->daysAgo(1),
            'end' => $this->now(),
            'positions' => [
                'President' => ['cand_007', 'cand_008', 'cand_009'],
                'Vice President' => ['cand_010', 'cand_011', 'cand_012'],
                'Senators' => ['cand_013', 'cand_014', 'cand_015', 'cand_016', 'cand_017', 'cand_018', 'cand_019', 'cand_020', 'cand_021', 'cand_022', 'cand_023', 'cand_024'],
            ],
        ];

        $voteCounter = 1;

        foreach ([$election001Config, $election003Config] as $config) {
            $voters = array_slice($allStudentIds, 0, $config['voter_count']);
            $positionCount = count($config['positions']);

            shuffle($voters);

            $this->logInfo("Generating votes for {$config['election_id']} ({$config['voter_count']} voters × {$positionCount} positions)...");

            foreach ($voters as $voterId) {
                foreach ($config['positions'] as $position => $candidatePool) {
                    $chosenCandidate = $candidatePool[array_rand($candidatePool)];
                    $voteKey = 'vote_'.str_pad($voteCounter, 5, '0', STR_PAD_LEFT);
                    $createdAt = $this->randomDateBetween($config['start'], $config['end']);

                    $this->set('votes', $voteKey, [
                        'id' => $voteKey,
                        'voter_id' => $voterId,
                        'voters_id' => $voterId,
                        'candidate_id' => $chosenCandidate,
                        'election_id' => $config['election_id'],
                        'position' => $position,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
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
                'id' => 'report_001',
                'election_id' => 'election_001',
                'generated_by' => 'ADM8N2K4Q6W0X1',
                'report_type' => 'vote_summary',
                'status' => 'completed',
                'file_path' => 'reports/election_001/vote_summary_2024.pdf',
                'file_name' => 'vote_summary_2024.pdf',
                'file_format' => 'pdf',
                'file_size_bytes' => 204800,
                'filters' => null,
                'summary' => [
                    'total_votes' => 400 * 3,
                    'total_voters' => 400,
                    'turnout_percent' => 100.0,
                    'positions' => ['President', 'Vice President', 'Senators'],
                ],
                'error_message' => null,
                'created_at' => $this->daysAgo(2),
                'updated_at' => $this->daysAgo(2),
            ],
            'report_002' => [
                'id' => 'report_002',
                'election_id' => 'election_001',
                'generated_by' => 'SAO5P1L8V3B7N6',
                'report_type' => 'candidate_results',
                'status' => 'completed',
                'file_path' => 'reports/election_001/candidate_results_2024.xlsx',
                'file_name' => 'candidate_results_2024.xlsx',
                'file_format' => 'xlsx',
                'file_size_bytes' => 51200,
                'filters' => ['position' => 'President'],
                'summary' => ['winner' => 'cand_001', 'winner_name' => 'Diana Lim', 'runner_up' => 'cand_002'],
                'error_message' => null,
                'created_at' => $this->daysAgo(2),
                'updated_at' => $this->daysAgo(2),
            ],
            'report_003' => [
                'id' => 'report_003',
                'election_id' => 'election_003',
                'generated_by' => 'ADM8N2K4Q6W0X1',
                'report_type' => 'voter_turnout',
                'status' => 'pending',
                'file_path' => null,
                'file_name' => null,
                'file_format' => null,
                'file_size_bytes' => null,
                'filters' => null,
                'summary' => [
                    'total_votes' => $this->activeVoterCount * 3,
                    'total_voters' => $this->activeVoterCount,
                    'turnout_percent' => $this->totalStudentCount > 0
                        ? round(($this->activeVoterCount / $this->totalStudentCount) * 100, 2)
                        : 0.0,
                    'positions' => ['President', 'Vice President', 'Senators'],
                ],
                'error_message' => null,
                'created_at' => $this->now(),
                'updated_at' => $this->now(),
            ],
            'report_004' => [
                'id' => 'report_004',
                'election_id' => 'election_001',
                'generated_by' => 'SAO5P1L8V3B7N6',
                'report_type' => 'party_results',
                'status' => 'failed',
                'file_path' => null,
                'file_name' => null,
                'file_format' => null,
                'file_size_bytes' => null,
                'filters' => null,
                'summary' => null,
                'error_message' => 'Timeout: PDF generation service unavailable.',
                'created_at' => $this->daysAgo(1),
                'updated_at' => $this->daysAgo(1),
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

    private function seedSystemActivity(): void
    {
        $this->logInfo('Seeding /system_activity...');

        $payload = function (
            string $userId,
            string $level,
            string $activity,
            string $createdAt,
            string $updatedAt,
            string $role,
            string $httpStatus,
            string $routeName,
            string $ipAddress,
            string $authChannel
        ): array {
            return [
                'user_id' => $userId,
                'level' => $level,
                'activity' => $activity,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'role' => $role,
                'http_status' => $httpStatus,
                'route_name' => $routeName,
                'ip_address' => $ipAddress,
                'auth_channel' => $authChannel,
            ];
        };

        $logs = [
            'log_001' => $payload(
                'ADM8N2K4Q6W0X1',
                'info',
                "Election 'SSC General Elections 2024' status changed to closed. (election_id=election_001)",
                $this->daysAgo(3),
                $this->daysAgo(3),
                'admin',
                '200',
                'view.elections',
                '192.168.1.10',
                'session',
            ),
            'log_002' => $payload(
                'STU-023-001',
                'info',
                'User STU-023-001 registered as a candidate for position President (election_001).',
                $this->daysAgo(25),
                $this->daysAgo(25),
                'student',
                '201',
                'view.candidates',
                '192.168.1.20',
                'session',
            ),
            'log_003' => $payload(
                'STU-025-001',
                'warning',
                'Duplicate vote attempt detected for voter STU-025-001 (election_001, position President).',
                $this->daysAgo(8),
                $this->daysAgo(8),
                'student',
                '409',
                'vote.submit',
                '192.168.1.30',
                'session',
            ),
            'log_004' => $payload(
                'ADM8N2K4Q6W0X1',
                'error',
                'Report generation failed for report_004 (reason: PDF service timeout).',
                $this->daysAgo(1),
                $this->daysAgo(1),
                'admin',
                '503',
                'view.reports-and-analytics',
                '192.168.1.10',
                'session',
            ),
            'log_005' => $payload(
                'guest',
                'critical',
                'Multiple failed login attempts detected from IP 192.168.1.50 (attempts: 10).',
                $this->now(),
                $this->now(),
                'guest',
                '401',
                'login',
                '192.168.1.50',
                'session',
            ),
        ];

        foreach ($logs as $key => $log) {
            match ($log['level']) {
                'warning' => $this->logWarning("System activity {$key}: {$log['activity']}"),
                'error', 'critical' => $this->logError("System activity {$key}: {$log['activity']}"),
                default => $this->logInfo("System activity {$key}: {$log['activity']}"),
            };
            $this->set('system_activity', $key, $log);
        }

        $this->logSuccess('System activity seeded successfully.');
    }

    private function seedSecurityLogs(): void
    {
        $this->logInfo('Seeding /security_logs...');

        $usersSnapshot = $this->db->getReference('/users')->getSnapshot();
        $students = [];

        if ($usersSnapshot->exists() && $usersSnapshot->getValue() !== null) {
            foreach ($usersSnapshot->getValue() as $user) {
                if (isset($user['role']) && $user['role'] === 'student') {
                    $students[] = $user;
                }
            }
        }

        $staticLogs = [
            'sec_log_001' => [
                'id' => 'sec_log_001',
                'student_id' => 'CS-2025-001',
                'first_name' => 'Jose',
                'last_name' => 'Perolino',
                'course' => 'Computer Science',
                'year_level' => '4th Year',
                'log_type' => 'duplicate_vote',
                'first_attempt' => '2025-12-05T10:43:00',
                'second_attempt' => '2025-12-05T10:43:00',
                'election_id' => 'election_001',
                'status' => 'blocked',
                'created_at' => $this->daysAgo(10),
            ],
            'sec_log_002' => [
                'id' => 'sec_log_002',
                'student_id' => 'IT-2025-035',
                'first_name' => 'Myles',
                'last_name' => 'Macrohon',
                'course' => 'Information Technology',
                'year_level' => '2nd Year',
                'log_type' => 'duplicate_vote',
                'first_attempt' => '2025-12-05T13:30:00',
                'second_attempt' => '2025-12-05T13:30:00',
                'election_id' => 'election_001',
                'status' => 'blocked',
                'created_at' => $this->daysAgo(10),
            ],
            'sec_log_003' => [
                'id' => 'sec_log_003',
                'student_id' => 'BA-2025-141',
                'first_name' => 'Honey',
                'last_name' => 'Malang',
                'course' => 'Business Administration',
                'year_level' => '3rd Year',
                'log_type' => 'rejected_fingerprint',
                'first_attempt' => '2025-12-05T08:41:00',
                'second_attempt' => '2025-12-05T08:41:00',
                'election_id' => 'election_001',
                'status' => 'blocked',
                'created_at' => $this->daysAgo(10),
            ],
            'sec_log_004' => [
                'id' => 'sec_log_004',
                'student_id' => 'CS-2025-225',
                'first_name' => 'Jahaira',
                'last_name' => 'Ampaso',
                'course' => 'Business Administration',
                'year_level' => '1st Year',
                'log_type' => 'denied_access',
                'first_attempt' => '2025-12-05T10:43:00',
                'second_attempt' => '2025-12-05T10:43:00',
                'election_id' => 'election_001',
                'status' => 'blocked',
                'created_at' => $this->daysAgo(10),
            ],
        ];

        foreach ($staticLogs as $key => $log) {
            $this->set('security_logs', $key, $log);
        }

        if (! empty($students)) {
            $logTypes = ['duplicate_vote', 'duplicate_vote', 'duplicate_vote', 'duplicate_vote', 'rejected_fingerprint', 'rejected_fingerprint', 'denied_access'];
            $sampleSize = min(6, count($students));
            $sampled = array_slice($students, 0, $sampleSize);
            $counter = 5;

            foreach ($sampled as $student) {
                $logType = $logTypes[array_rand($logTypes)];
                $attemptTime = $this->randomDateBetween($this->daysAgo(15), $this->daysAgo(1));
                $key = 'sec_log_'.str_pad($counter, 3, '0', STR_PAD_LEFT);

                $this->set('security_logs', $key, [
                    'id' => $key,
                    'student_id' => $student['student_id'] ?? 'N/A',
                    'first_name' => $student['first_name'] ?? '',
                    'last_name' => $student['last_name'] ?? '',
                    'course' => $student['course'] ?? 'N/A',
                    'year_level' => $student['year_level'] ?? 'N/A',
                    'log_type' => $logType,
                    'first_attempt' => $attemptTime,
                    'second_attempt' => $attemptTime,
                    'election_id' => 'election_001',
                    'status' => 'blocked',
                    'created_at' => $attemptTime,
                ]);

                $counter++;
            }

            $this->logSuccess("Dynamic security logs written for {$sampleSize} real students.");
        }

        $this->logSuccess('Security logs seeded successfully.');
    }
}
