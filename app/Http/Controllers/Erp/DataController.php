<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class DataController extends Controller
{
    // 获取管理员列表
    public function get_admin(Request $request)
    {
        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :10;
        if($keywords){
            $count = Admin::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('username','like',"%{$keywords}%");
            })->count();
            $data = Admin::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('username','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();
        }else{
            $count = Admin::count();
            $data = Admin::orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }





}