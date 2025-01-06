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
            $table->string('other_name')->nullable()->default('')->after('last_name');
            $table->string('organization_name')->nullable()->default('')->after('organization_role');  
            $table->string('qualification')->nullable()->default('')->after('organization_name'); 
            $table->string('course')->nullable()->default('')->after('qualification'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['other_name', 'organization_name', 'qualification', 'course']);  
        });
    }
};
