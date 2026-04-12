<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Database;

class ElectionSeeder extends Seeder
{
    protected Database $db;
    private function logSuccess(string $message): void
    {
        $this->command->line("  <fg=green>{$message}</>");
        Log::info($message);
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

    private function daysFromNow(int $days): string
    {
        return now()->addDays($days)->toISOString();
    }
    public function run(): void
    {
        $this->initFirebase();
        $this->logInfo('');
        $this->logInfo('Seeding Election Settings, Positions, and Candidates...');
        $this->logInfo('');
        $this->seedElectionSettings();
        $this->seedPositions();
        $this->seedCandidates();
        $this->logInfo('');
        $this->logSuccess('Election seeding complete!');
        $this->logInfo('');
    }
    private function seedElectionSettings(): void
    {
        $this->logInfo('Seeding /election_settings...');
        $this->db->getReference('/election_settings')->set([
            'id'            => 'election_main',
            'election_name' => 'SSC General Elections 2025',
            'semester'      => '2nd Semester',
            'academic_year' => '2024-2025',
            'start_date'    => $this->daysFromNow(5),
            'end_date'      => $this->daysFromNow(7),
            'opening_time'  => '08:00 AM',
            'closing_time'  => '05:00 PM',
            'status'        => 'upcoming',
            'created_at'    => $this->daysAgo(10),
            'updated_at'    => $this->daysAgo(1),
        ]);
        $this->logSuccess('Election settings seeded successfully.');
    }
    private function seedPositions(): void
    {
        $this->logInfo('Seeding /positions...');
        $positions = [
            'pos_001' => [
                'id'            => 'pos_001',
                'election_id'   => 'election_main',
                'position_name' => 'President',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_002' => [
                'id'            => 'pos_002',
                'election_id'   => 'election_main',
                'position_name' => 'Vice President',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_003' => [
                'id'            => 'pos_003',
                'election_id'   => 'election_main',
                'position_name' => 'Secretary',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_004' => [
                'id'            => 'pos_004',
                'election_id'   => 'election_main',
                'position_name' => 'Treasurer',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_005' => [
                'id'            => 'pos_005',
                'election_id'   => 'election_main',
                'position_name' => 'Auditor',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_006' => [
                'id'            => 'pos_006',
                'election_id'   => 'election_main',
                'position_name' => 'PRO',
                'max_votes'     => 1,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
            'pos_007' => [
                'id'            => 'pos_007',
                'election_id'   => 'election_main',
                'position_name' => 'Senators',
                'max_votes'     => 9,
                'created_at'    => $this->daysAgo(10),
                'updated_at'    => $this->daysAgo(10),
            ],
        ];

        foreach ($positions as $key => $position) {
            $this->set('positions', $key, $position);
        }

        $this->logSuccess(count($positions) . ' positions seeded successfully.');
    }

    private function seedCandidates(): void
    {
        $this->logInfo('Seeding /candidates...');

        $courses = [
            'Computer Science',
            'Information Technology',
            'Business Administration',
            'Civil Engineering',
            'Electrical Engineering',
        ];

        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];

        $parties = ['Unity Party', 'Progreso Alliance', 'Independent'];

        $candidates = [
            'cand_e001' => [
                'id'             => 'cand_e001',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_001',
                'position_name'  => 'President',
                'full_name'      => 'Honey Malang',
                'course'         => 'Business Administration',
                'year'           => '3rd Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Transparency and student welfare in all SSC decisions. Committed to inclusive governance.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e002' => [
                'id'             => 'cand_e002',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_001',
                'position_name'  => 'President',
                'full_name'      => 'Myles Macrohon',
                'course'         => 'Information Technology',
                'year'           => '2nd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Progress through unity — a stronger voice for every student. Innovation in student governance.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e003' => [
                'id'             => 'cand_e003',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_002',
                'position_name'  => 'Vice President',
                'full_name'      => 'Jose Perolino',
                'course'         => 'Computer Science',
                'year'           => '4th Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Bridging the gap between students and administration. Advocating for academic support.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e004' => [
                'id'             => 'cand_e004',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_002',
                'position_name'  => 'Vice President',
                'full_name'      => 'Jahaira Ampaso',
                'course'         => 'Business Administration',
                'year'           => '1st Year',
                'political_party' => 'Independent',
                'platform_agenda' => 'An independent voice dedicated solely to student needs and welfare.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e005' => [
                'id'             => 'cand_e005',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_003',
                'position_name'  => 'Secretary',
                'full_name'      => 'Maria Santos',
                'course'         => 'Computer Science',
                'year'           => '2nd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Organized, transparent, and accountable documentation for every student.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e006' => [
                'id'             => 'cand_e006',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_003',
                'position_name'  => 'Secretary',
                'full_name'      => 'Ana Reyes',
                'course'         => 'Information Technology',
                'year'           => '3rd Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Every SSC decision documented clearly and accessible to all students.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e007' => [
                'id'             => 'cand_e007',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_004',
                'position_name'  => 'Treasurer',
                'full_name'      => 'Carlos Dela Cruz',
                'course'         => 'Business Administration',
                'year'           => '4th Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Fiscal responsibility and full financial transparency for every student fund.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e008' => [
                'id'             => 'cand_e008',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_004',
                'position_name'  => 'Treasurer',
                'full_name'      => 'Rosa Villanueva',
                'course'         => 'Civil Engineering',
                'year'           => '3rd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Zero tolerance for misuse of funds. Full transparency in all financial matters.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e009' => [
                'id'             => 'cand_e009',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_005',
                'position_name'  => 'Auditor',
                'full_name'      => 'Miguel Garcia',
                'course'         => 'Electrical Engineering',
                'year'           => '4th Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Rigorous auditing to ensure every fund is properly accounted for.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e010' => [
                'id'             => 'cand_e010',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_005',
                'position_name'  => 'Auditor',
                'full_name'      => 'Sofia Lim',
                'course'         => 'Computer Science',
                'year'           => '2nd Year',
                'political_party' => 'Independent',
                'platform_agenda' => 'Independent auditing with zero conflict of interest.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e011' => [
                'id'             => 'cand_e011',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_006',
                'position_name'  => 'PRO',
                'full_name'      => 'Elena Torres',
                'course'         => 'Information Technology',
                'year'           => '1st Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Amplifying the student voice on every platform. Creative and consistent communications.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e012' => [
                'id'             => 'cand_e012',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_006',
                'position_name'  => 'PRO',
                'full_name'      => 'Ramon Aquino',
                'course'         => 'Business Administration',
                'year'           => '2nd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Ensuring all SSC news reaches every student through modern channels.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e013' => [
                'id'             => 'cand_e013',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'James Cortes',
                'course'         => 'Civil Engineering',
                'year'           => '3rd Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Infrastructure and academic facilities improvement for all students.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e014' => [
                'id'             => 'cand_e014',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Carley Serato',
                'course'         => 'Computer Science',
                'year'           => '2nd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Technology-driven solutions for student concerns and campus life.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e015' => [
                'id'             => 'cand_e015',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Breant Cortes',
                'course'         => 'Electrical Engineering',
                'year'           => '4th Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Championing scholarship access and financial aid for deserving students.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e016' => [
                'id'             => 'cand_e016',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Carley Cobarde',
                'course'         => 'Business Administration',
                'year'           => '1st Year',
                'political_party' => 'Independent',
                'platform_agenda' => 'Mental health and student wellness programs as a top priority.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e017' => [
                'id'             => 'cand_e017',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Luis Ramos',
                'course'         => 'Information Technology',
                'year'           => '3rd Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Open and transparent budget allocation for all student organizations.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e018' => [
                'id'             => 'cand_e018',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Carmen Castro',
                'course'         => 'Civil Engineering',
                'year'           => '2nd Year',
                'political_party' => 'Unity Party',
                'platform_agenda' => 'Stronger representation for engineering students in SSC decisions.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
            'cand_e019' => [
                'id'             => 'cand_e019',
                'election_id'    => 'election_main',
                'position_id'    => 'pos_007',
                'position_name'  => 'Senators',
                'full_name'      => 'Antonio Gonzales',
                'course'         => 'Computer Science',
                'year'           => '4th Year',
                'political_party' => 'Progreso Alliance',
                'platform_agenda' => 'Expanding campus sports and extracurricular programs for all students.',
                'image_url'      => '',
                'status'         => 'active',
                'created_at'     => $this->daysAgo(8),
                'updated_at'     => $this->daysAgo(8),
            ],
        ];

        foreach ($candidates as $key => $candidate) {
            $this->set('candidates', $key, $candidate);
        }

        $this->logSuccess(count($candidates) . ' candidates seeded successfully.');
        $this->logInfo('Breakdown:');
        $this->logInfo('  President:      2 candidates');
        $this->logInfo('  Vice President: 2 candidates');
        $this->logInfo('  Secretary:      2 candidates');
        $this->logInfo('  Treasurer:      2 candidates');
        $this->logInfo('  Auditor:        2 candidates');
        $this->logInfo('  PRO:            2 candidates');
        $this->logInfo('  Senators:       7 candidates');
    }
}
