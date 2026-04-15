<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Factory;

class FreshFirebaseSeeder extends Seeder
{
    protected Database $db;

    private function initFirebase(): void
    {
        $this->db = (new Factory)
            ->withServiceAccount(base_path(env('FIREBASE_CREDENTIALS')))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    }

    public function run(): void
    {
        $this->initFirebase();

        // DELETE ALL existing Firebase data
        $this->db->getReference('/')->remove();

        // Seed all in order
        $this->call([
            FirebaseSeeder::class,
            ElectionSeeder::class,
            SecurityLogsOnlySeeder::class,
        ]);
    }
}
