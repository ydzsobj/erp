<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {

        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        //getSql();
        if($keywords){
            //$count = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->count();
            //$data = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->offset(($page-1)*$limit)->limit($limit)->get();
            $count = Inventory::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->count();
            $data = Inventory::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = Inventory::count();
            $data = Inventory::orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个信息
    public function goods($id)
    {
        $data = PurchaseOrderInfo::where('purchase_order_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }
}
