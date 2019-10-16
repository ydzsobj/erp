<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category_name' => ['required','between:2,20'],
            'category_code' => ['required','digits:2'],
        ];
    }

    public function messages()
    {
        return [
            'category_name.required'=>'分类名称不能为空',
            'category_name.between'=>'分类名称必须是2-20个字符',
            'category_code.required'=>'分类编码不能为空',
            'category_code.digits'=>'分类编码必须是2位数字',
        ];
    }

}
