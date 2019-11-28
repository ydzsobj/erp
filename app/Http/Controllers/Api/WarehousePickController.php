<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\WarehousePick;
use Illuminate\Http\Request;

class WarehousePickController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {
        $data = (new WarehousePick())->search($request);
        $count = $data->count();

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取数据列表
    public function order(Request $request)
    {
        $pick = (new WarehousePick())->first();
        $ids=explode(',',$pick->pick_ids);
        $data = Order::whereIn('id', $ids)->get();
        $count = $data->count();

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }




}
