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
        Schema::create('add_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_id')->constrained('adds')->onDelete('cascade');
            $table->string('job_type'); 
            $table->string('employment_form'); 
            $table->decimal('salary_from', 10, 2)->nullable();
            $table->decimal('salary_to', 10, 2)->nullable();
            $table->string('experience_level')->nullable(); 
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_jobs');
    }
};
