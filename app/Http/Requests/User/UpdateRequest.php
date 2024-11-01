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
            'middle_initial' => 'nullable|string|max:1',
            'email' => "required|email",
            'course' => 'required|string|in:Computer Science,Information Technology,Information System',
            'block' => 'required|string|max:255',
            'password' => 'nullable|min:8',
            'role' => 'required|string|in:admin,student,coordinator',
            'host_training_establishment_id' => 'nullable',
        ];
    }
}
