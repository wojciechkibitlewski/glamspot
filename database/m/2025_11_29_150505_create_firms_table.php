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
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('firm_name');
            $table->string('firm_city')->nullable();
            $table->string('firm_postalcode')->nullable();
            $table->string('firm_address')->nullable();
            $table->string('firm_region')->nullable();
            $table->string('firm_nip')->nullable();
            $table->string('firm_www')->nullable();
            $table->string('firm_email')->nullable();
             $table->string('firm_phone')->nullable();
            $table->string('firm_logo')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firms');
    }
};
