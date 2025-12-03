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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone', 32)->nullable()->after('avatar');
            $table->string('city')->nullable()->after('phone');
            $table->string('code', 5)->nullable()->unique()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('city');
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropColumn('code');

        });
    }
};
