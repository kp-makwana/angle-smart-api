<?php

namespace App\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
          'current_password' => [
            'required',
            function ($attribute, $value, $fail) {
              if (!Hash::check($value, auth()->user()->password)) {
                $fail('The current password is incorrect.');
              }
            },
          ],
          'password' => [
            'required',
            'confirmed',
            Password::min(8)
              ->letters()
              ->numbers(),
          ],
        ];
    }
}
