<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('erp.admin.login');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            "username"=>"required|between:2,16",
            "password"=>"required|between:6,20",
        ],[
            "username.required"=>"用户名必须填写",
            "username.between"=>"请输入2-16位的用户名",
            "password.required"=>"密码必须填写",
            "password.between"=>"请输入6-20位的密码",
        ]);
        if($validator->fails()){
            return response()->json(['code'=>'1','msg'=>$validator->errors()->first()]);
        }
        $ip=$request->getClientIp();
        //登录验证
        if(Auth::guard('admin')->attempt($request->only(['username','password']))){

            $time=date('Y-m-d H:i:s',time());
            $admin=Admin::where('username',$request->input('username'))->first();
            if($admin->status!='1'){
                $this->admin_log($request->input('username'),$request->input('password'),2,$ip,'Login_fail','此账户无法使用!请联系管理员!');
                return response()->json(['code'=>'1','msg'=>'此账户无法使用!请联系管理员!']);
            }
            if($admin->admin_ip!=null){
                Cookie::forever('l_ip',$admin->admin_ip);
                Cookie::forever('l_time',$admin->updated_at);
                Cookie::forever('l_num',$admin->admin_num);
            }else{
                Cookie::forever('l_ip','初次登陆');
                Cookie::forever('l_time','初次登陆');
                Cookie::forever('l_num','初次登陆');
            }
            $admin->admin_ip=$ip;
            $admin->admin_num=$admin->admin_num+1;
            $admin->save();
            $this->admin_log($request->input('username'),'',1,$ip,'Login_success','登录成功！');
            Log::notice($admin->username.'账号登录！IP:'.$ip.'!时间:'.$time);
            return response()->json(['code'=>'0','msg'=>'登录成功']);
        }else{
            $this->admin_log($request->input('username'),$request->input('password'),2,$ip,'Login_fail','用户名或密码错误');
            return response()->json(['code'=>'1','msg'=>'用户名或密码错误']);
        }

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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //退出
    public function logout()
    {
        Auth::logout();
        return redirect('/admins/login');
    }


}
