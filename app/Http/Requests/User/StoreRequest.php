<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

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
            'email' => 'required|email|unique:users,email',
            'course' => 'required|string|in:Computer Science,Information Technology,Information System',
            'block' => 'required|string|max:255',
            'password' => 'required|min:8',
            'role' => 'required|string|in:admin,student,coordinator',
            'host_training_establishment_id' => 'nullable',
        ];
    }
}
