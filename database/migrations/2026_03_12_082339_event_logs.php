<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['info', 'warning', 'error', 'critical']);
            $table->text('message');
            $table->json('context')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index('level', 'idx_event_logs_level');
            $table->index('created_at', 'idx_event_logs_created');
            $table->index(['level', 'created_at'], 'idx_event_logs_level_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
