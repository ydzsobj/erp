<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryImportLog;
use Illuminate\Http\Request;

class InventoryImportLogController extends Controller
{
    public function index(Request $request){

        $obj = new InventoryImportLog();

        $data = $obj->get_data($request);

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data, 'count' => $data->total()]);
    }
}
