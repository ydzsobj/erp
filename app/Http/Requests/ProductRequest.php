<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_name' => ['required','unique:product'],
            'product_english' => ['required'],
            'product_price' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'product_name.required'=>'产品名称不能为空',
            'product_english.required'=>'英文名称不能为空',
            'product_price.required'=>'销售价不能为空',
            'product_name.unique'=>'产品名称重复',
        ];
    }


}
