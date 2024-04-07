<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'login'    => 'required|string|max:64',
            'password' => 'required|string|max:64',
        ];
    }
    public function messages()
    {
        return [
            'login.required' => 'Поле не может быть пустым.',
            'login.max' => 'Поле не может содержать более :max символов.',
            'password.required' => 'Поле не может быть пустым.',
            'password.max' => 'Поле не может содержать более :max символов.',
        ];
    }
}
