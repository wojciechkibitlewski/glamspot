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
        Schema::create('add_machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('add_id')->constrained('adds')->cascadeOnDelete();
            $table->string('state');  
            $table->string('availability_type'); 
            $table->string('price_unit')->nullable();
            $table->boolean('deposit_required')->default(false);
            $table->unsignedBigInteger('subcategory_id')->nullable(); 
            $table->timestamps();

            $table->index('add_id');
            $table->index('subcategory_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_machines');
    }
};
