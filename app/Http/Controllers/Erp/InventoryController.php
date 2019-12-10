<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Imports\InventoryImport;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use App\Models\Order;
use App\Models\Warehouse;
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
     * 引导页
     */
    public function guide(Request $request)
    {
        $warehouse_id = $request->get('warehouse_id');

        switch($warehouse_id){
            // case Inventory::SZ_WAREHOUSE_ID:
            //     //深圳仓
            //     return view('erp.inventory.SZ.index', compact('warehouse_id'));
            // break;
            case Inventory::YN_WAREHOUSE_ID:
                //印尼仓引导页
                return view('erp.inventory.YN.guide', compact('warehouse_id'));
            break;
            case Inventory::YN_VIRTUAL_WAREHOUSE_ID:
                //印尼虚拟引导页
                return view('erp.inventory.YN_VIRTUAL.guide', compact('warehouse_id'));
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
     * 库存总账
     */
    public function all(){
        return view('erp.inventory.all.index');
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

            if($item['in_num'] < 1){
                continue;
            }
            DB::transaction(function () use ($item, $admin) {

                //修改出库状态
                $inventory_info = InventoryInfo::find($item['id']);
                $inventory_info->out_status = 1;
                $inventory_info->save();

                //修改真实仓库存
                $inventory = Inventory::by_goods_sku($item['warehouse_id'], $item['goods_sku']);
                $inventory->out_num += $item['in_num'];
                $inventory->stock_num -= $item['in_num'];
                $inventory->save();

                InventoryInfo::create([
                    'out_num' => $item['in_num'],
                    'in_num' => 0,
                    'out_status' => 1,
                    'goods_sku' => $item['goods_sku'],
                    'warehouse_id' => Inventory::YN_VIRTUAL_WAREHOUSE_ID,
                    'stock_type' => InventoryInfo::STOCK_TYPE_NAME_OUT,
                    'user_id' => $admin->id
                ]);
            }, 3);
        }

        return response()->json(['success' => true, 'msg' => 'ok']);
    }

    //印尼仓出库
    public function yn_out(Request $request){

        $admin = Auth::user();

        $out_data = $request->post('out_data');

        $error_msg = '';

        foreach($out_data as $item){

            if($item['goods_num'] < 1){
                continue;
            }

            $inventory = Inventory::by_goods_sku($item['warehouse_id'], $item['goods_sku']);
            if(!$inventory){
                $error_msg .= '当前仓库没有该sku的商品：'.$item['goods_sku'];
                continue;
            }

            $inventory->stock_num -= $item['goods_num'];
            if($inventory->stock_num < 0){
                $error_msg .= $item['sku_name']. '库存不足';
                continue;
            }

            DB::transaction(function () use ($item, $admin, $error_msg,$inventory) {

                //修改真实仓库存
                $inventory->out_num += $item['goods_num'];
                $inventory->save();

                InventoryInfo::create([
                    'out_num' => $item['goods_num'],
                    'in_num' => 0,
                    'out_status' => 1,
                    'goods_sku' => $item['goods_sku'],
                    'warehouse_id' => Inventory::YN_WAREHOUSE_ID,
                    'stock_type' => InventoryInfo::STOCK_TYPE_NAME_ORDER_OUT,
                    'user_id' => $admin->id,
                    'targetable_type' => 'order',
                    'targetable_id' => $item['id']
                ]);

                //更新订单状态
                Order::where('id', $item['id'])->update([
                    'order_status' => 6 //已出库
                ]);

            }, 3);
        }

        $error_msg = $error_msg ?: 'ok';

        return response()->json(['success' => true, 'msg' => $error_msg ]);
    }

    //印尼仓入库页面
    public function yn_in_create(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        $virtual_warehouse_id = 4;

        return view('erp.inventory.YN.in_create', compact('warehouse_id','virtual_warehouse_id'));
    }

     //印尼虚拟仓入库页面
     public function yn_virtual_in_create(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        return view('erp.inventory.YN_VIRTUAL.in_create', compact('warehouse_id'));
    }

     //印尼虚拟仓出库页面
     public function yn_virtual_out_create(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        return view('erp.inventory.YN_VIRTUAL.out_create', compact('warehouse_id'));
    }

     //印尼仓出库页面
     public function yn_out_create(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        return view('erp.inventory.YN.out_create', compact('warehouse_id'));
    }

    //问题件
    public function problems_create(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        return view('erp.inventory.YN_VIRTUAL.problems_create', compact('warehouse_id'));
    }

    //印尼仓入库
    public function yn_in(Request $request){

        $admin = Auth::user();

        $in_data = $request->post('in_data');

        // dd($in_data);

        foreach($in_data as $item){

            if($item['out_num'] < 1){
                continue;
            }
            DB::transaction(function () use ($item, $admin) {

                $inventory_info = InventoryInfo::find($item['id']);
                $inventory_info->out_status = 2;//入库状态 已入真实库
                $inventory_info->save();

                $existed_data = Inventory::by_goods_sku(Warehouse::YN_WAREHOUSE_ID, $item['goods_sku']);

                if($existed_data){
                    //sku已存在，追加库存信息 //虚拟仓出的 = 真实仓待入库的
                    $existed_data->stock_num += intval($item['out_num']);
                    $existed_data->in_num += intval($item['out_num']);
                    $existed_data->save();

                }else{
                    //sku新入库 添加数据
                    $mod = Inventory::create([
                        'goods_sku' => $item['goods_sku'],
                        'warehouse_id' => Warehouse::YN_WAREHOUSE_ID,
                        'stock_num' => intval($item['out_num']),
                        'in_num' => intval($item['out_num'])
                    ]);
                }

                //添加详情
                InventoryInfo::create([
                    'goods_sku' => $item['goods_sku'],
                    'warehouse_id' => Warehouse::YN_WAREHOUSE_ID,
                    'in_num' => intval($item['out_num']),
                    'stock_type' => '确认入库',
                    'user_id' => $admin->id
                ]);

            }, 3);
        }

        return response()->json(['success' => true, 'msg' => 'ok']);
    }

     /*
     *设置仓位库位
     */
    public function update_fields(Request $request, $id){

        $field = $request->post('update_field');
        $value = $request->post('update_field_value');

        //更新操作
        $inventory = Inventory::find($id);
        $inventory->{$field} = $value;

        $result = $inventory->save();

        $msg = $result ? '保存成功':'保存失败';
        $success = $result ? true : false;
        return response()->json(['success' => $success, 'msg' => $msg]);

    }

    //标记问题件
    public function update_status(Request $request, $id){

        $success = true;
        $msg = 'ok';
        $action = $request->post('action');

        DB::transaction(function () use ($request, $id, $action) {

            switch($action){
                case 'set_problem':
                    $status = 3;
                    break;
                case 'remove_problem':
                    $status = 4;
                    break;
                default:
                    return false;
            }

            if($status){
                $inventory_info = InventoryInfo::find($id);
                $inventory_info->out_status = $status;//问题件
                $result = $inventory_info->save();
            }

            if($status == 3){
                 //减去库存
                $inventory = Inventory::by_goods_sku($request->post('warehouse_id'), $inventory_info->goods_sku);
                $inventory->stock_num -= $inventory_info->in_num;
                $inventory->save();
            }

        });

        return returned($success, $msg);

    }

}
