<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Imports\InventoryImport;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $warehouse_id = $request->get('warehouse_id');

        switch($warehouse_id){
            case Inventory::SZ_WAREHOUSE_ID:
                //深圳仓首页列表
                return view('erp.inventory.SZ.index', compact('warehouse_id'));
            break;
            case Inventory::YN_WAREHOUSE_ID:
                //印尼仓首页列表
                return view('erp.inventory.YN.index', compact('warehouse_id'));
            break;
            case Inventory::YN_VIRTUAL_WAREHOUSE_ID:
                //印尼虚拟仓首页列表
                return view('erp.inventory.YN_VIRTUAL.index', compact('warehouse_id'));
            break;
            default:
                return false;
        }

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
        //
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
        //
    }

    /*
     *设置商品SKU所在仓位库位
     */
    public function goods_position(Request $request,$id){
        //更新操作
        $result = Inventory::find($id);
        $result->goods_position = $request->goods_position;
        return $result->save()?'0':'1';
    }

    //导入数据
    public function import(Request $request){
        $file_path = $request->post('path');
        $warehouse_id = $request->post('warehouse_id');
        Excel::import(new InventoryImport($warehouse_id), str_replace('storage','',$file_path), 'public');
    }

    //印尼虚拟仓出库
    public function yn_virtual_out(Request $request){

        $admin = Auth::user();

        $out_data = $request->post('out_data');

        foreach($out_data as $item){

            if($item['stock_num'] < 1){
                continue;
            }
            DB::transaction(function () use ($item, $admin) {
                $inventory = Inventory::find($item['id']);
                $inventory->stock_num -= $item['stock_num'];
                $inventory->out_num += $item['stock_num'];
                $inventory->save();

                $insert = [
                    [
                        'out_num' => $item['stock_num'],
                        'goods_sku' => $item['goods_sku'],
                        'warehouse_id' => Inventory::YN_VIRTUAL_WAREHOUSE_ID,
                        'stock_type' => InventoryInfo::STOCK_TYPE_NAME_OUT,
                        'user_id' => $admin->id,
                        'created_at' => Carbon::now()
                    ],
                    [
                        'in_num' => $item['stock_num'],
                        'goods_sku' => $item['goods_sku'],
                        'warehouse_id' => Inventory::YN_WAREHOUSE_ID,
                        'stock_type' => InventoryInfo::STOCK_TYPE_NAME_IN,
                        'user_id' => $admin->id,
                        'created_at' => Carbon::now()
                    ]
                ];

                DB::table('inventory_info')->insert($insert);
            }, 3);
        }

        return response()->json(['success' => true, 'msg' => 'ok']);
    }

}
