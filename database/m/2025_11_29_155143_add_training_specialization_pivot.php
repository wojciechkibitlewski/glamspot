<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_training_specialization', function (Blueprint $table): void {
            $table->foreignId('add_training_id')->constrained('add_trainings')->onDelete('cascade');
            $table->foreignId('specialization_id')->constrained('add_training_specializations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('add_training_specialization');
    }
};