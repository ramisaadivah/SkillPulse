<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        DB::table('job_roles')->truncate();

        DB::table('job_roles')->insert([
            [
                'title' => 'Backend Developer',
                'required_skills' => json_encode(['php', 'laravel', 'sql', 'git', 'javascript']),
                'created_at' => now(),
            ],
            [
                'title' => 'AI Engineer',
                'required_skills' => json_encode(['python', 'machine learning', 'nlp', 'sql', 'git']),
                'created_at' => now(),
            ],
            [
                'title' => 'Cybersecurity Analyst',
                'required_skills' => json_encode(['networking', 'linux', 'python', 'sql', 'security']),
                'created_at' => now(),
            ],
            [
                'title' => 'Frontend Architect',
                'required_skills' => json_encode(['react', 'javascript', 'tailwind', 'typescript', 'figma']),
                'created_at' => now(),
            ],
            [
                'title' => 'Data Scientist',
                'required_skills' => json_encode(['python', 'sql', 'pandas', 'numpy', 'statistics']),
                'created_at' => now(),
            ],
            [
                'title' => 'Fullstack Engineer',
                'required_skills' => json_encode(['php', 'laravel', 'react', 'sql', 'docker']),
                'created_at' => now(),
            ],
            [
                'title' => 'Database Administrator',
                'required_skills' => json_encode(['sql', 'mysql', 'postgresql', 'linux', 'cloud']),
                'created_at' => now(),
            ]
        ]);
    }
}