<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportOrderRequest extends FormRequest
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
            'path'=> 'required',
            'country_id'=> 'required',
        ];
    }

    public function messages()
    {
        return [
            'path.required' => '上传文件失败',
            'country_id.required' => '国家地区必须选择',
        ];
    }
}
