<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PurchaseWarehouse;
use App\Models\PurchaseWarehouseInfo;
use Illuminate\Http\Request;

class PurchaseWarehouseController extends Controller
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
            $count = PurchaseWarehouse::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('purchase_warehouse_code','like',"%{$keywords}%");
            })->count();
            $data = PurchaseWarehouse::with('warehouse')->where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('purchase_warehouse_code','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = PurchaseWarehouse::count();
            $data = PurchaseWarehouse::with('warehouse')->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //采购入库展示
    public function show(Request $request,$id){
        $model = new PurchaseWarehouse();
        list($data,$count) = $model->search($request,$id);
        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }


    //获取单个采购订单信息
    public function goods($id)
    {
        $data = PurchaseWarehouseInfo::where('purchase_warehouse_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

}
