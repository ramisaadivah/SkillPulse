<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('analysis_histories', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->integer('score');
            $table->json('matched_skills');
            $table->json('missing_skills');
            $table->timestamps(); // This stores the date of analysis
        });
    }

    public function down(): void {
        Schema::dropIfExists('analysis_histories');
    }
};