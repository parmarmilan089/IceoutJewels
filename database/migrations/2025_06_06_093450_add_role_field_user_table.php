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
            $table->string('phone')->after('password')->nullable();
            $table->boolean('is_admin')->after('phone')->default(false);
            $table->string('profile')->after('is_admin')->nullable();
            $table->boolean('status')->after('profile')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
            $table->dropColumn('profile');
            $table->dropColumn('status');
        });
    }
};
