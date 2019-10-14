<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        return view('erp.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        //存储表单信息
        $result = Admin::insert([
            'username'=>$request->username,
            'admin_name'=>$request->admin_name,
            'password'=>bcrypt($request->password),
            'status'=>$request->status,
        ]);
        return $result ? '0' : '1';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('erp.admin.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //编辑操作
        $data = Admin::find($id);
        return view('erp.admin.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminRequest $request, $id)
    {
        //更新操作
        $result = Admin::find($id);
        if($request->password!=''){
            $result->password = bcrypt($request->password);
        }
        $result->admin_name = $request->admin_name;
        $result->status = $request->status;
        return $result->save()?'0':'1';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除操作
        $result = Admin::find($id);
        return $result->delete()?'0':'1';
    }

    /**
     * 修改密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function password(Request $request)
    {
        if($request->isMethod('get')){
            return view('erp.admin.password');
        }elseif($request->isMethod('post')){
            $validator=Validator::make($request->all(),[
                'old_password'=>'required',
                "password"=>"required|between:6,20|confirmed",
            ],[
                'old_password.required'=>'原始密码不能为空',
                "password.required"=>"密码必须填写",
                "password.between"=>"请输入6-20位的密码",
                "password.confirmed"=>"新密码不一致，请重新输入！",
            ]);
            if($validator->fails()){
                return response()->json(['code'=>'1','msg'=>$validator->errors()->first()]);
            }
            $ip=$request->getClientIp();
            $data=$request->only('password','old_password');
            $admin = Admin::find(Auth::user()->id);
            if(!Hash::check($data['old_password'],$admin->password)){
                return response()->json(['code'=>'1','msg'=>'原始密码输入错误，如忘记，请联系超级管理员！']);
            }
            $admin->password = bcrypt($data['password']);
            $msg = $admin->save();
            //添加补货单日志
            if(!$msg){
                return response()->json(['code'=>'1','msg'=>'密码修改失败！']);
            }
            $this->admin_log(Auth::user()->username,'',3,$ip,'password_modify','密码修改成功！');
            Log::notice(Auth::user()->username.'密码修改成功！IP:'.$ip);
            return response()->json(['code'=>'0','msg'=>'密码修改成功~']);
        }
    }

}
