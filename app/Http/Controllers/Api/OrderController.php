<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderInfo;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {
        $order = new Order();
        list($orders,$count) = $order->search($request);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$orders]);
    }

    //获取订单导入列表
    public function import(Request $request)
    {
        $order = new Order();
        list($orders,$count) = $order->searchImport($request);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$orders]);
    }

    //获取订单查询列表
    public function list(Request $request)
    {
        $order = new Order();
        list($orders,$count)  = $order->searchAll($request);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$orders]);
    }

    //获取单个订单信息
    public function goods($id)
    {
        $data = OrderInfo::where('order_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

}
