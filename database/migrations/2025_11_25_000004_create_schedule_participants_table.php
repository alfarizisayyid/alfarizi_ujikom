<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedule_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('selection_schedules')->onDelete('cascade');
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->enum('status', ['scheduled', 'attended', 'absent', 'postponed'])->default('scheduled');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->unique(['schedule_id', 'registration_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_participants');
    }
};
