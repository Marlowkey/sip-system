<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:5',
            'email' => 'required|email|unique:users,email',
            'student_number' => 'nullable|string|max:255',
            'course' => 'required|string|in:Computer Science,Information Technology,Information System',
            'block' => 'nullable|string|max:255',
            'password' => 'required|min:8',
            'role' => 'required|string|in:admin,student,coordinator',
            'host_training_establishment_id' => 'nullable',
            'school_year_id' => 'nullable|exists:school_years,id',
        ];
    }
}
