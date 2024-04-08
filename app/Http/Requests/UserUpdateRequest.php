<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends ApiRequest
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
    public function rules()
    {
        $user = auth()->user();

        return [
            'name'          => 'string|max:64',
            'surname'       => 'string|max:64',
            'patronymic'    => 'nullable|string|max:64',
            'login'         => 'string|min:5|max:64|unique:users,login,'.$user->id,
            'password'      => 'string|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'birth'         => 'date|date_format:Y-m-d|before_or_equal:2010-01-01',
            'email'         => 'email|max:64|unique:users,email,'.$user->id,
            'telephone'     => 'integer|digits_between:1,20|unique:users,telephone,'.$user->id,
        ];
    }
    public function messages()
    {
        return [
            'name.max' => 'Поле должно содержать не более :max символов.',

            'surname.max' => 'Поле должно содержать не более :max символов.',

            'patronymic.max' => 'Поле должно содержать не более :max символов.',

            'login.min' => 'Поле должно содержать не менее :min символов.',
            'login.max' => 'Поле должно содержать не более :max символов.',
            'login.unique' => 'Такое уже существует.',

            'password.min' => 'Поле должно содержать не менее :min символов.',
            'password.max' => 'Поле должно содержать не более :max символов.',
            'password.regex' => 'Пароль должен содержать как минимум одну цифру, одну заглавную букву, одну малую букву и один специальный символ.',

            'birth.date' => 'Поле должно быть датой.',
            'birth.date_format' => 'Неверный формат поля. Используйте формат ГГГГ-ММ-ДД.',
            'birth.before_or_equal' => 'Дата рождения должна быть до или равна :date.',

            'email.email' => 'Поле должно быть действительным адресом электронной почты.',
            'email.max' => 'Поле должно содержать не более :max символов.',
            'email.unique' => 'Такае уже существует.',

            'telephone.integer' => 'Поле должно быть целым числом.',
            'telephone.digits_between' => 'Поле должно содержать от :min до :max цифр.',
            'telephone.unique' => 'Такое уже существует.',
        ];
    }

}
