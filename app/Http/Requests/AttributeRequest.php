<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'attr_name' => ['required'],
            'attr_english' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'attr_name.required'=>'属性名称不能为空',
            'attr_english.required'=>'英文名称不能为空',
        ];
    }


}
