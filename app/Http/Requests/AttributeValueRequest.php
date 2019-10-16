<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeValueRequest extends FormRequest
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
            'attr_value_name' => ['required'],
            'attr_value_english' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'attr_value_name.required'=>'属性值不能为空',
            'attr_value_english.required'=>'英文名称不能为空',
        ];
    }


}
