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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Pribadi
            $table->string('full_name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('phone');
            $table->string('email')->unique();
            
            // Data Alamat
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->string('postal_code');
            
            // Data Identitas
            $table->string('ktp_number')->unique();
            $table->date('ktp_expiry');
            
            // Data Pendidikan
            $table->string('education_level');
            $table->string('institution');
            $table->year('graduation_year');
            
            // Status Pendaftaran
            $table->enum('status', ['draft', 'submitted', 'pending_review', 'accepted', 'rejected'])->default('draft');
            $table->text('rejection_reason')->nullable();
            
            // Tanggal Verifikasi
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
