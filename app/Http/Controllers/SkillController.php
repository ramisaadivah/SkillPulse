<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
   
    public function add(Request $request)
    {
        $request->validate([
            'skill' => 'required|string|max:30'
        ]);

        $user = Auth::user();
        
        // Get existing skills or empty array if null
        $skills = $user->detected_skills ?? [];

       
        $newSkill = trim($request->skill);

        if (!in_array($newSkill, $skills)) {
            $skills[] = $newSkill;
            $user->detected_skills = $skills;
            $user->save();
        }

        return back()->with('status', 'Skill added successfully!');
    }

    
    public function remove(Request $request)
    {
        $user = Auth::user();
        $skills = $user->detected_skills ?? [];

        // Filter out the skill to be removed
        $skills = array_filter($skills, function($skill) use ($request) {
            return $skill !== $request->skill;
        });

        // Reset array keys and save
        $user->detected_skills = array_values($skills);
        $user->save();

        return back()->with('status', 'Skill removed.');
    }
}