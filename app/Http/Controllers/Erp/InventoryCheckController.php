<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Imports\InventoryCheckImport;
use App\Models\Inventory;
use App\Models\InventoryCheck;
use App\Models\InventoryCheckInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryCheckController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $filename = $request->upload_file;
        $warehouse_id = $request->get('warehouse_id');
        $inventory_check_code = $this->createInventoryCheckCode('P');
        $import = new InventoryCheckImport($warehouse_id,$inventory_check_code);

        $collection = Excel::import($import, $filename);
        //$collection = Excel::toCollection($import, $filename);
        //dd($collection);
        $msg = session()->get('excel');
        return response()->json(['code'=>'0','msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return view('erp.inventory_check.show',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除
        $result = InventoryCheck::find($id);
        return $result->delete()?'0':'1';
    }

    //盘点单导入
    public function import($id)
    {
        return view('erp.inventory_check.import',compact('id'));
    }

    //盘点单导入
    public function change(Request $request,$id='')
    {
        $warehouse_id = $request->get('warehouse_id');
        $check_info = InventoryCheckInfo::where(function ($query) use ($id,$warehouse_id){
            $query->where('id',$id)->where('warehouse_id',$warehouse_id);
        })->first();

        $this->doInventoryCheck($check_info,$warehouse_id);
        $check_info->inventory_check_info_status = 1;

        return $check_info->save()?'0':'1';

    }

    //批量更新
    public function all(Request $request){
        $ids = $request->get('ids');
        $ids=explode(',',$ids);
        $warehouse_id = $request->get('warehouse_id');


        $data = [
            'inventory_check_info_status' => '1',
        ];

        foreach ($ids as $key=>$value){
            if(empty($value)) continue;
            $id = intval($value);
            $check_info = InventoryCheckInfo::where(function ($query) use ($id,$warehouse_id){
                $query->where('id',$id)->where('warehouse_id',$warehouse_id);
            })->first();
            if($check_info['inventory_check_info_status']!='0') continue;
            $this->doInventoryCheck($check_info,$warehouse_id);
        }


        $res = InventoryCheckInfo::whereIn('id', $ids)->update($data);
        return $res ? '0':'1';



    }


    //
    public function doInventoryCheck($check_info,$warehouse_id){
        if($check_info['goods_num']<0) return '1';
        $goods_sku = $check_info['goods_sku'];
        $inventory = Inventory::where(function ($query) use ($goods_sku,$warehouse_id){
            $query->where('goods_sku',$goods_sku)->where('warehouse_id',$warehouse_id);
        })->first();


        $inventoryArr['goods_sku'] = $check_info['goods_sku'];
        $inventoryArr['warehouse_id'] = $check_info['warehouse_id'];
        $inventoryArr['stock_num'] = $check_info['goods_num'];
        $inventoryArr['stock_unused_num'] = $check_info['goods_num'];
        $inventoryArr['goods_position'] = $check_info['goods_position']??'';
        $inventoryArr['goods_created'] = Carbon::now();


        if($inventory){
            $balance = $check_info['goods_num'] - $inventory->stock_num;
            $inventory->stock_num = $check_info['goods_num'];
            $inventory->stock_unused_num = $inventory->stock_used_num + $balance;

            $inventory->save();
        }else{

            Inventory::create($inventoryArr);
        }


    }




}
