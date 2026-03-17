<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('report_type', [
                'vote_summary',
                'voter_turnout',
                'candidate_results',
                'party_results',
            ]);
            $table->string('file_path')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index(['election_id', 'report_type'], 'idx_reports_election_type');
            $table->index('generated_by',                 'idx_reports_generated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
