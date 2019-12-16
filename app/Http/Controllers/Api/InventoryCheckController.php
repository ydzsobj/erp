<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryCheck;
use App\Models\InventoryCheckInfo;
use Illuminate\Http\Request;

class InventoryCheckController extends Controller
{
    //获取数据列表
    public function show(Request $request,$id)
    {
        $model = new InventoryCheck();
        list($data,$count) = $model->search($request,$id);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个采购订单信息
    public function goods($id)
    {
        $data = InventoryCheckInfo::where('inventory_check_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

}
