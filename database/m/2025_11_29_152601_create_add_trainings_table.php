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
        Schema::create('add_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_id')->constrained('adds')->onDelete('cascade');
            $table->string('type'); 
            $table->integer('seats')->nullable();
            $table->boolean('has_certificate')->default(false);
            $table->integer('duration_hours')->nullable();
            $table->string('organizer')->nullable();
            $table->boolean('is_online')->default(false);
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('promo_price', 10, 2);
            $table->text('audience')->nullable();
            $table->longText('description')->nullable();
            $table->longText('program')->nullable();
            $table->longText('bonuses')->nullable();
            $table->string('signup_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_trainings');
    }
};
