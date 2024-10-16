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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('id');
            $table->string('company_name')->nullable()->after('id');
            $table->string('country')->nullable()->after('id');
            $table->text('address')->nullable()->after('id');
            $table->string('city')->nullable()->after('id');
            $table->string('post_code')->nullable()->after('id');
            $table->string('phone')->nullable()->after('id');
            $table->string('email')->unique()->after('id');
            $table->string('password')->nullable()->after('id');
            $table->string('code')->nullable()->after('id');
            $table->timestamp('email_verified_at')->nullable()->after('id');
            $table->rememberToken()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['first_name','last_name','company_name','email','password','email_verified_at', 'country', 'address', 'city', 'post_code','phone','code','remember_token']);
        });
    }
};
