<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class WarehouseOutController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {
        $order = new Order();
        $orders = $order->searchOut($request);
        $count = $orders->count();

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$orders]);
    }
}
