<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends ApiRequest
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
        return [
            'name'        => 'string|max:64',
            'description' => 'nullable|string',
            'price'        => ['numeric', 'min:0', 'regex:/^\d{1,8}(\.\d{1,2})?$/'], // Формат decimal(10,2)
            'quantity'    => 'integer|min:1',
            'photo'       => 'nullable|file|mimes:jpeg,jpg,png,webp|max:4096', //до 4мб
            'category_id' => 'integer|min:1',
        ];
    }
    public function messages()
    {
        return [
            'name.max'            => 'Поле должно содержать не более :max символов.',
            'price.numeric'       => 'Поле должно быть числом.',
            'price.min'           => 'Поле должно быть не менее :min.',
            'price.regex'         => 'Недопустимый формат поля.',
            'quantity.integer'    => 'Поле должно быть целым числом.',
            'quantity.min'        => 'Поле должно быть не менее :min.',
            'photo.file'          => 'Поле должно быть файлом.',
            'photo.mimes'         => 'Поле должно быть файлом типа: jpeg, jpg, png, webp.',
            'photo.max'           => 'Файл в поле должен быть не больше :max килобайт.',
            'category_id.integer' => 'Поле должно быть целым числом.',
            'category_id.min'     => 'Поле должно быть не менее :min.',
        ];
    }
}
