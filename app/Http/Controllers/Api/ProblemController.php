<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    //


    //问题订单展示
    public function show(Request $request,$id){
        $model = new Order();
        list($data,$count) = $model->searchProblem($request,$id);
        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }


}
