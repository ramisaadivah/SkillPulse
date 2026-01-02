<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function showUploadForm() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userSkills = $user->detected_skills ?? [];

        $jobsQuery = DB::table('job_roles');

        if (!empty($userSkills)) {
            $jobsQuery->where(function($query) use ($userSkills) {
                foreach ($userSkills as $skill) {
                    $cleanSkill = strtolower(trim($skill));
                    $query->orWhere(DB::raw('LOWER(title)'), 'LIKE', '%' . $cleanSkill . '%')
                          ->orWhere('required_skills', 'LIKE', '%' . $cleanSkill . '%');
                }
            });
        }

        $jobs = $jobsQuery->limit(4)->get();

       
        if ($jobs->isEmpty()) {
            $jobs = DB::table('job_roles')->latest()->limit(4)->get();
        }
        
        $history = DB::table('analysis_histories')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('upload', compact('jobs', 'history'));
    }

    public function analyze(Request $request) {
        
        set_time_limit(0); 

        $request->validate([
            'resume' => 'required_if:resume_source,upload|nullable|mimes:pdf|max:2048',
            'jd_file' => 'required|mimes:pdf|max:2048',
            'resume_source' => 'required|in:upload,saved'
        ]);

        try {
            $user = Auth::user();
            $jdFile = $request->file('jd_file');
            
            if ($request->resume_source === 'saved' && $user->resume_path) {
                $resumeContent = Storage::disk('public')->get($user->resume_path);
                $resumeName = basename($user->resume_path);
                $path = $user->resume_path;
            } else {
                $resumeFile = $request->file('resume');
                $resumeContent = file_get_contents($resumeFile);
                $resumeName = $resumeFile->getClientOriginalName();
                $path = $resumeFile->store('resumes', 'public');
            }

          
            $response = Http::timeout(120)->attach(
                'resume', $resumeContent, $resumeName
            )->attach(
                'jd', file_get_contents($jdFile), $jdFile->getClientOriginalName()
            )->post('https://skillpulse-4.onrender.com/compare-job-resume');
            

            if ($response->failed()) {
                throw new \Exception("The AI backend at Port 8000 did not respond.");
            }

            $data = $response->json();
            
            
            $newSkills = $data['matched'] ?? [];
            $existingSkills = $user->detected_skills ?? [];
            $user->detected_skills = array_values(array_unique(array_merge($existingSkills, $newSkills)));
            $user->resume_path = $path;
            $user->save();

            return view('results', [
                'jobTitle' => $data['job_title'] ?? 'Job Analysis',
                'matched' => $newSkills,
                'missing' => $data['missing'] ?? [],
                'score' => round($data['score'] ?? 0),
                'roadmap' => $this->getProjectRoadmap($data['missing'] ?? []),
                'other_careers' => $data['other_careers'] ?? []
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function getProjectRoadmap($missingSkills) {
        $roadmap = [];
        foreach ($missingSkills as $skill) {
            $roadmap[$skill] = ['topic' => $skill, 'project' => "Build a project to master $skill"];
        }
        return $roadmap;
    }
}