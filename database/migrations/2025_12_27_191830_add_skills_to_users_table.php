<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if resume_path exists before adding it
            if (!Schema::hasColumn('users', 'resume_path')) {
                $table->string('resume_path')->nullable()->after('email');
            }
            
            // Check if detected_skills exists before adding it
            if (!Schema::hasColumn('users', 'detected_skills')) {
                $table->text('detected_skills')->nullable()->after('resume_path');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['resume_path', 'detected_skills']);
        });
    }
};