<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {

        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :10;
        //getSql();
        if($keywords){
            //$count = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->count();
            //$data = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->offset(($page-1)*$limit)->limit($limit)->get();
            $count = PurchaseOrder::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('product_name','like',"%{$keywords}%");
            })->count();
            $data = PurchaseOrder::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('product_name','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = PurchaseOrder::count();
            $data = PurchaseOrder::orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }
}
