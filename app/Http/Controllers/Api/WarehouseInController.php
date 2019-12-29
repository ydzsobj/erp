<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WarehouseIn;
use App\Models\WarehouseInInfo;
use Illuminate\Http\Request;

class WarehouseInController extends Controller
{
    //

    //采购入库展示
    public function show(Request $request,$id){
        $model = new WarehouseIn();
        list($data,$count) = $model->search($request,$id);
        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个采购订单信息
    public function goods($id)
    {
        $data = WarehouseInInfo::where('warehouse_in_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }



}
