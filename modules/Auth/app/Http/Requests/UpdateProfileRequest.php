<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $userId],
            'phone' => ['nullable', 'string', 'max:20'],
            'name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Username already exists',
            'username.max' => 'Username must not exceed 255 characters',
            'phone.max' => 'Phone must not exceed 20 characters',
            'name.max' => 'Name must not exceed 255 characters',
        ];
    }
}
