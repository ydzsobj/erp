<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseWarehouseInfoRequest extends FormRequest
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
            'real_num' => ['integer','min:0'],
        ];
    }

    public function messages()
    {
        return [
            'real_num.min'=>'验货数量不能小于0',
            'real_num.integer'=>'验货数量有误，请重试！',
        ];
    }
}
