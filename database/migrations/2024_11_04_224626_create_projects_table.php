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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('banner_id')->nullable()->constrained('assets')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('tags')->nullable();
            $table->enum('category', ['map', 'discussion', 'link']);
            $table->enum('status', ['public', 'private', 'draft'])->default('draft');
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->unsignedBigInteger('shares')->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
