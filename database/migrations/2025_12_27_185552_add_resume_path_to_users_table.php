<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if column exists before adding to avoid "Duplicate column" errors
            if (!Schema::hasColumn('users', 'resume_path')) {
                $table->string('resume_path')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'detected_skills')) {
                $table->json('detected_skills')->nullable()->after('resume_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['resume_path', 'detected_skills']);
        });
    }
};
