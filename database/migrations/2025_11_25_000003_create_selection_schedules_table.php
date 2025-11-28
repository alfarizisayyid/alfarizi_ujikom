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
        Schema::create('selection_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('stage', ['interview', 'physical_test', 'psychological_test', 'medical_test', 'final_selection']);
            $table->dateTime('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('planned');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('selection_schedules');
    }
};
