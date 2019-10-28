<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class ProductUnitController extends Controller
{
    //获取列表数据
    public function index(Request $request)
    {
        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        if($keywords){
            $count = ProductUnit::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('brand_name','like',"%{$keywords}%");
            })->count();
            $data = ProductUnit::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('brand_name','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();
        }else{
            $count = ProductUnit::count();
            $data = ProductUnit::orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }
}
