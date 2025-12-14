<?php

namespace App\Http\Requests\V1\Accounts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        'nickname'      => 'required|string|max:255',
        'client_id' => [
          'required',
          'string',
          'max:255',
          Rule::unique('accounts', 'client_id')->where(fn ($query) =>
              $query->where('user_id', auth()->id())
            ),
        ],
        'pin'           => 'required|max:4',
        'api_key'       => 'required|string|max:255',
        'client_secret' => 'nullable|string|max:255',
        'totp_secret'   => 'required|string|max:255',
      ];
    }
}
