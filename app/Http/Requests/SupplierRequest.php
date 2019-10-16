<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'supplier_name' => ['required'],
            'supplier_person' => ['required'],
            'supplier_phone' => ['required','digits_between:6,12'],
        ];
    }


    public function messages()
    {
        return [
            'supplier_name.required'=>'供应商名称不能为空',
            'supplier_person.required'=>'联系人姓名不能为空',
            'supplier_phone.required'=>'联系人电话不能为空',
            'supplier_phone.digits_between'=>'联系人电话必须是6-12位数字',
        ];
    }


}
