<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\InventoryInfo;
use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\OrderLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('erp.warehouse_out.index');
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
        return view('erp.warehouse_out.show',compact('id'));
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
     * 出库
     */
    public function out(Request $request){
        $ids = $request->get('ids');
        $warehouse_id = $request->get('warehouse_id');
        $ids=explode(',',$ids);

        foreach ($ids as $key=>$value){
            if(empty($value)) continue;
            $orderLogArr[$key] = [
                'order_id' => intval($value),
                'user_id' =>  Auth::guard('admin')->user()->id,
                'order_status' => 6,
                'order_text' => '订单已出库',
                'created_at' => Carbon::now(),
            ];

            $order_info = OrderInfo::with(['inventory'=>function($query) use ($warehouse_id){
                $query->where('warehouse_id',$warehouse_id);
            }])->where('order_id',$value)->get();
            foreach ($order_info as $k=>$v){
                $inventory = $v->inventory[0];
                $stock_num = $inventory['stock_num'];
                $inventory['stock_num'] = $stock_num - $v['goods_num'];
                $inventory['stock_used_num'] = $inventory['stock_used_num'] - $v['goods_num'];
                $inventory['out_num'] = $inventory['out_num'] + $v['goods_num'];


                $inventoryInfoArr[$key]['goods_id'] = $v['goods_id'];
                $inventoryInfoArr[$key]['warehouse_id'] = $warehouse_id;
                $inventoryInfoArr[$key]['goods_sku'] = $v['goods_sku'];
                $inventoryInfoArr[$key]['goods_name'] = $v['goods_name'];
                $inventoryInfoArr[$key]['out_num'] = $v['goods_num'];
                $inventoryInfoArr[$key]['out_money'] = $v['goods_money'];
                $inventoryInfoArr[$key]['stock_num'] = $stock_num  - $v['goods_num'];
                $inventoryInfoArr[$key]['stock_code'] = 'K'.date('YmdHis', time());
                $inventoryInfoArr[$key]['created_at'] = Carbon::now();


                $inventory->save();
            }

        }
        OrderLog::insert($orderLogArr);    //订单日志记录
        InventoryInfo::insert($inventoryInfoArr);  //库存日志

        $data = [
            'ex_at' => Carbon::now(),
            'ex_status' => 2,
            'ex_id' => Auth::user()->id,
            'order_status' => 6,
        ];

        $result = Order::whereIn('id', $ids)->update($data);

        return $result ? '0':'1';
    }






}
