<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends ApiRequest
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
            'name'          => 'required|string|max:64',
            'surname'       => 'required|string|max:64',
            'patronymic'    =>          'nullable|string|max:64',
            'login'         => 'required|string|min:5|max:64|unique:users',
            'password'      => 'required|string|min:8|max:64|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'birth'         => 'required|date|date_format:Y-m-d|before_or_equal:2010-01-01|after_or_equal:1930-01-01',
            'email'         => 'required|email|max:64|unique:users',
            'telephone'     => 'required|integer|digits_between:1,20|unique:users',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Поле обязательно для заполнения.',
            'name.max' => 'Поле должно содержать не более :max символов.',

            'surname.required' => 'Поле обязательно для заполнения.',
            'surname.max' => 'Поле должно содержать не более :max символов.',

            'patronymic.max' => 'Поле должно содержать не более :max символов.',

            'login.required' => 'Полобязательно для заполнения.',
            'login.min' => 'Поле должно содержать не менее :min символов.',
            'login.max' => 'Поле должно содержать не более :max символов.',
            'login.unique' => 'Такое уже существует.',

            'password.required' => 'Поле обязательно для заполнения.',
            'password.min' => 'Поле должно содержать не менее :min символов.',
            'password.max' => 'Поле должно содержать не более :max символов.',
            'password.regex' => 'Пароль должен содержать как минимум одну цифру, одну заглавную букву, одну малую букву и один специальный символ.',

            'birth.required' => 'Поле обязательно для заполнения.',
            'birth.date' => 'Поле должно быть датой.',
            'birth.date_format' => 'Неверный формат поля. Используйте формат ГГГГ-ММ-ДД.',
            'birth.before_or_equal' => 'Дата рождения должна быть до или равна :date.',

            'email.required' => 'Поле обязательно для заполнения.',
            'email.email' => 'Поле должно быть действительным адресом электронной почты.',
            'email.max' => 'Поле должно содержать не более :max символов.',
            'email.unique' => 'Такое уже существует.',

            'telephone.required' => 'Поле обязательно для заполнения.',
            'telephone.integer' => 'Поле должно быть целым числом.',
            'telephone.digits_between' => 'Поле должно содержать от :min до :max цифр.',
            'telephone.unique' => 'Такое уже существует.',
        ];
    }

}
