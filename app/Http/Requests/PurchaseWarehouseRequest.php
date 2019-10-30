<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseWarehouseRequest extends FormRequest
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
            'supplier_id' => ['required'],
            'warehouse_id' => ['required','integer'],
        ];
    }

    public function messages()
    {
        return [
            'category_name.required'=>'分类名称不能为空',

            'warehouse_id.required'=>'仓库名不能为空',
            'warehouse_id.integer'=>'仓库名有误，请重试！',
        ];
    }


}
