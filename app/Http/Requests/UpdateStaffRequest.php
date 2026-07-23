<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $staffId = is_object($this->route('staff')) ? $this->route('staff')->id : $this->route('staff');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:staff,email,' . $staffId . '|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|string|in:Active,Inactive,Suspended',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered in the system.',
            'password.confirmed' => 'The password confirmation does not match.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }
}
