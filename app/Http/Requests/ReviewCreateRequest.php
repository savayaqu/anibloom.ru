<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewCreateRequest extends ApiRequest
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
            'rating'     => 'required|integer|min:1|max:5',
            'textReview' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'rating.required'       => 'Поле обязательно для заполнения.',
            'rating.integer'  => 'Поле должно быть целым числом.',
            'rating.min'      => 'Поле должно быть не менее :min.',
            'rating.max'      => 'Поле должно быть не более :max.',
            'textReview.required'       => 'Поле обязательно для заполнения.',
        ];
    }
}
