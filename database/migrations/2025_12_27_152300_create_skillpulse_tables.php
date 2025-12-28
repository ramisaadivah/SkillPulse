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
        // 1. Table for all possible skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Python"
            $table->string('category')->nullable(); // e.g., "Programming"
            $table->timestamps();
        });

        // 2. Table for Job Roles (The Benchmark)
        Schema::create('job_roles', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "Data Scientist"
            $table->json('required_skills'); // Stores an array of skill names: ["python", "sql", "stats"]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_roles');
        Schema::dropIfExists('skills');
    }
};
