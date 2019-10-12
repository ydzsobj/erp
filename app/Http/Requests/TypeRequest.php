<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeRequest extends FormRequest
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
            'type_name' => ['required'],
            'type_english' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'type_name.required'=>'类型名称不能为空',
            'type_english.required'=>'英文名称不能为空',
        ];
    }


}
