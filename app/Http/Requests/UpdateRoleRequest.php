<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = is_object($this->route('role')) ? $this->route('role')->id : $this->route('role');
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $roleId,
            'description' => 'nullable|string',
            'status' => 'required|string|in:Active,Inactive',
        ];
    }
}
