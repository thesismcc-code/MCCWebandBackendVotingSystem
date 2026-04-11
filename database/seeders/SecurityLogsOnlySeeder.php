<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class SecurityLogsOnlySeeder extends Seeder
{
    protected Database $db;
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
    private function initFirebase(): void
    {
        $credentialsPath = env('FIREBASE_CREDENTIALS');
        $databaseUrl     = env('FIREBASE_DATABASE_URL');

        if (! $credentialsPath) {
            $msg = 'FIREBASE_CREDENTIALS is not set in your .env file.';
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        if (! $databaseUrl) {
            $msg = 'FIREBASE_DATABASE_URL is not set in your .env file.';
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        $absolutePath = base_path($credentialsPath);

        if (! file_exists($absolutePath)) {
            $msg = "Firebase credentials file not found at: {$absolutePath}";
            $this->logError($msg);
            throw new \RuntimeException($msg);
        }

        $factory = (new Factory)
            ->withServiceAccount($absolutePath)
            ->withDatabaseUri($databaseUrl);

        $this->db = $factory->createDatabase();
    }
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

    private function randomDateBetween(string $start, string $end): string
    {
        $startTs = strtotime($start);
        $endTs   = strtotime($end);
        return date('Y-m-d\TH:i:s', rand($startTs, $endTs));
    }


    public function run(): void
    {
        $this->initFirebase();

        $this->logInfo('');
        $this->logInfo('Seeding /security_logs only — all other collections untouched.');
        $this->logInfo('');

        $this->seedSecurityLogs();

        $this->logInfo('');
        $this->logSuccess('Security logs seeding complete!');
        $this->logInfo('');
    }

    private function seedSecurityLogs(): void
    {
        $this->logInfo('Seeding /security_logs...');

        $usersSnapshot = $this->db->getReference('/users')->getSnapshot();
        $students      = [];

        if ($usersSnapshot->exists() && $usersSnapshot->getValue() !== null) {
            foreach ($usersSnapshot->getValue() as $user) {
                if (isset($user['role']) && $user['role'] === 'student') {
                    $students[] = $user;
                }
            }
        }

        $this->logInfo('Found ' . count($students) . ' student(s) in /users.');

        // ── Static entries — matches the screenshot exactly ──────────────────
        $staticLogs = [
            'sec_log_001' => [
                'id'             => 'sec_log_001',
                'student_id'     => 'CS-2025-001',
                'first_name'     => 'Jose',
                'last_name'      => 'Perolino',
                'course'         => 'Computer Science',
                'year_level'     => '4th Year',
                'log_type'       => 'duplicate_vote',
                'first_attempt'  => '2025-12-05T10:43:00',
                'second_attempt' => '2025-12-05T10:43:00',
                'election_id'    => 'election_001',
                'status'         => 'blocked',
                'created_at'     => $this->daysAgo(10),
            ],
            'sec_log_002' => [
                'id'             => 'sec_log_002',
                'student_id'     => 'IT-2025-035',
                'first_name'     => 'Myles',
                'last_name'      => 'Macrohon',
                'course'         => 'Information Technology',
                'year_level'     => '2nd Year',
                'log_type'       => 'duplicate_vote',
                'first_attempt'  => '2025-12-05T13:30:00',
                'second_attempt' => '2025-12-05T13:30:00',
                'election_id'    => 'election_001',
                'status'         => 'blocked',
                'created_at'     => $this->daysAgo(10),
            ],
            'sec_log_003' => [
                'id'             => 'sec_log_003',
                'student_id'     => 'BA-2025-141',
                'first_name'     => 'Honey',
                'last_name'      => 'Malang',
                'course'         => 'Business Administration',
                'year_level'     => '3rd Year',
                'log_type'       => 'rejected_fingerprint',
                'first_attempt'  => '2025-12-05T08:41:00',
                'second_attempt' => '2025-12-05T08:41:00',
                'election_id'    => 'election_001',
                'status'         => 'blocked',
                'created_at'     => $this->daysAgo(10),
            ],
            'sec_log_004' => [
                'id'             => 'sec_log_004',
                'student_id'     => 'CS-2025-225',
                'first_name'     => 'Jahaira',
                'last_name'      => 'Ampaso',
                'course'         => 'Business Administration',
                'year_level'     => '1st Year',
                'log_type'       => 'denied_access',
                'first_attempt'  => '2025-12-05T10:43:00',
                'second_attempt' => '2025-12-05T10:43:00',
                'election_id'    => 'election_001',
                'status'         => 'blocked',
                'created_at'     => $this->daysAgo(10),
            ],
        ];

        foreach ($staticLogs as $key => $log) {
            $this->set('security_logs', $key, $log);
        }

        // ── Dynamic entries from real seeded students ────────────────────────
        if (! empty($students)) {
            $logTypes   = ['duplicate_vote', 'duplicate_vote', 'duplicate_vote', 'duplicate_vote', 'rejected_fingerprint', 'rejected_fingerprint', 'denied_access'];
            $sampleSize = min(6, count($students));
            $sampled    = array_slice($students, 0, $sampleSize);
            $counter    = 5;

            foreach ($sampled as $student) {
                $logType     = $logTypes[array_rand($logTypes)];
                $attemptTime = $this->randomDateBetween($this->daysAgo(15), $this->daysAgo(1));
                $key         = 'sec_log_' . str_pad($counter, 3, '0', STR_PAD_LEFT);

                $this->set('security_logs', $key, [
                    'id'             => $key,
                    'student_id'     => $student['student_id'] ?? 'N/A',
                    'first_name'     => $student['first_name'] ?? '',
                    'last_name'      => $student['last_name'] ?? '',
                    'course'         => $student['course'] ?? 'N/A',
                    'year_level'     => $student['year_level'] ?? 'N/A',
                    'log_type'       => $logType,
                    'first_attempt'  => $attemptTime,
                    'second_attempt' => $attemptTime,
                    'election_id'    => 'election_001',
                    'status'         => 'blocked',
                    'created_at'     => $attemptTime,
                ]);

                $counter++;
            }

            $this->logSuccess("Dynamic security logs written for {$sampleSize} real students.");
        }

        $this->logSuccess('Security logs seeded successfully.');
    }
}
