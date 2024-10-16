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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('user_name')->after('id');
            $table->string('email')->unique()->after('id');
            $table->string('password')->after('id');
            $table->string('profile')->nullable()->after('id');
            $table->rememberToken()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['user_name', 'email', 'password', 'profile', 'remember_token']);
        });
    }
};
