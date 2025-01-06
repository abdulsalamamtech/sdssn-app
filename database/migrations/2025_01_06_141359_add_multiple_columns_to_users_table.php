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
            $table->after('last_name', function ($table){
                $table->string('other_name')->nullable()->default('');
            });
            $table->after('organization_role', function ($table){ 
                $table->string('organization_name')->nullable()->default('');  
                $table->string('qualification')->nullable()->default(''); 
                $table->string('course')->nullable()->default(''); 
            });
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
