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
            $table->string('company_name')->nullable()->after('id');
            $table->string('country')->nullable()->after('id');
            $table->text('address')->nullable()->after('id');
            $table->string('city')->nullable()->after('id');
            $table->string('post_code')->nullable()->after('id');
            $table->string('phone')->nullable()->after('id');
            $table->string('code')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'country', 'address', 'city', 'post_code','phone','code']);
        });
    }
};
