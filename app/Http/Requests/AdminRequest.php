<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            'username' => ['required','between:4,20'],
            'password' => ['nullable','between:6,20','confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户名不能为空',
            'username.between'=>'用户名必须是4-20个字符',
            'password.nullable'=>'用户密码不能为空',
            'password.between'=>'用户密码必须是6-20个字符',
            "password.confirmed"=>"新密码不一致，请重新输入！",
        ];
    }
}
