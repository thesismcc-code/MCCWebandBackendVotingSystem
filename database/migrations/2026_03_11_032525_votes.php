<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('candidate_id')
                  ->constrained('candidates')
                  ->cascadeOnDelete();
            $table->foreignId('election_id')
                  ->constrained('elections')
                  ->cascadeOnDelete();
            $table->string('position', 100);
            $table->timestamp('created_at')->nullable();
            $table->unique(['voter_id', 'position', 'election_id'], 'unique_vote_per_position');
            $table->index(['election_id', 'candidate_id'], 'idx_votes_election_candidate');
            $table->index(['election_id', 'position'],     'idx_votes_election_position');
            $table->index('voter_id',                      'idx_votes_voter');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
