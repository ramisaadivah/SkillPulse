<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            
            // SkillPulse Profile Fields
            'education_level' => ['nullable', 'string', 'max:255'],
            'interests' => ['nullable', 'string', 'max:1000'],
            
            // NEW: Resume File Validation
            // mimes:pdf ensures only PDF files are uploaded
            // max:2048 limits the file size to 2MB (2048 KB)
            'resume' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }
}
