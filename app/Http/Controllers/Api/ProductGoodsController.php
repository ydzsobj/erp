<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductGoods;
use Illuminate\Http\Request;

class ProductGoodsController extends Controller
{
    //获取列表信息
    public function index(Request $request)
    {

        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        //getSql();
        if($keywords){
            //$count = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->count();
            //$data = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->offset(($page-1)*$limit)->limit($limit)->get();
            $count = ProductGoods::where(function ($query) use ($keywords){
                $query->where('sku_code','like',"%{$keywords}%")
                    ->orWhere('sku_name','like',"%{$keywords}%");
            })->count();
            $data = ProductGoods::where(function ($query) use ($keywords){
                $query->where('sku_code','like',"%{$keywords}%")
                    ->orWhere('sku_name','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = ProductGoods::count();
            $data = ProductGoods::orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }
}
