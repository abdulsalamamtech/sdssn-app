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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('username');
            $table->string('email')->unique();
            $table->string('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->string('security_question');
            $table->string('answer');
            $table->string('profession')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('membership_status', ['free', 'trial', 'premium', 'gold', 'annual'])->default('free');
            $table->date('dob')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable()->comment('as city or lga for address');
            $table->string('state');
            $table->string('country')->nullable();

            // Organization
            $table->string('organization')->nullable();
            $table->string('organization_category')->nullable();
            $table->string('organization_role')->nullable();


            // Role and who assign the role
            $table->enum('role', ['user', 'moderator', 'admin', 'super-admin'])->default('user');
            $table->foreignId('assigned_by')->nullable()->constrained('users');

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();


        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
