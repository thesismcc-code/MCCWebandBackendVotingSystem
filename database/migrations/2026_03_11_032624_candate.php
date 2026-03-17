<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('party_list_id')
                  ->nullable()
                  ->constrained('party_lists')
                  ->nullOnDelete();
            $table->foreignId('election_id')
                  ->constrained('elections')
                  ->cascadeOnDelete();

            $table->string('position', 100);
            $table->text('manifesto')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->index(['election_id', 'position']);
            $table->index('status');
            $table->index('party_list_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
