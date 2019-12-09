<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\InventoryImport;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {

        $obj = new Inventory();
        $data = $obj->get_data($request);
        $count = $data->total();

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个信息
    public function goods(Request $request,$goods_id)
    {
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        $data = InventoryInfo::where(function($query) use($goods_id,$request) {
            $query->where(['goods_id'=>$goods_id,'warehouse_id'=>$request->warehouse_id]);
        })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

    /**
     * 库存详情
     */
    public function api_info(Request $request){

        $info_obj = new InventoryInfo();

        $data = $info_obj->get_data($request);

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data, 'count' => $data->total()]);
    }

}
