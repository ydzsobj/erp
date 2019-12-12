<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Http\Request;

class WarehouseOutController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {
        $model = new Order();
        list($data,$count)  = $model->searchOut($request);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //采购入库展示
    public function show(Request $request,$id){
        $model = new Order();
        list($data,$count) = $model->searchOut($request,$id);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }


}
