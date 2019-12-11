<?php

namespace App\Http\Controllers\Erp;

use App\Exports\OrderExport;
use App\Exports\UsersExport;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Warehouse;
use App\Models\WarehousePick;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;

class WarehousePickController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('erp.warehouse_pick.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $warehouse = Warehouse::where('warehouse_status','1')->get();
        return view('erp.warehouse_pick.create',compact('warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        //存储表单信息
        $pick_code = $this->createWarehousePickCode('J');

        $pick_ids=array();
        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                $pick_ids[] = $value['id'];
                Order::where('id',$value['id'])->update(['order_status'=>5,'ex_status'=>1]);
                $orderLogArr[] = [
                    'order_id' => $value['id'],
                    'user_id' =>  Auth::guard('admin')->user()->id,
                    'order_status' => 5,
                    'order_text' => '订单拣货中',
                    'created_at' => Carbon::now(),
                ];
                OrderLog::insert($orderLogArr);    //订单日志记录
            }
        }
        $arr = [
            'pick_code' => $pick_code,
            'pick_ids' => implode(',',$pick_ids),
            'pick_name' => $request->pick_name,
            'pick_phone' => $request->pick_phone,
            'warehouse_id' => $request->warehouse_id,
            'user_id' => Auth::guard('admin')->user()->id,
            'pick_text' => $request->pick_text,
            'picked_at' => $request->picked_at,
            'pick_status' => 0,
            'created_at' => Carbon::now(),
        ];

        $result = WarehousePick::insert($arr);
        return $result ? '0' : '1';

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


    //拣货单导出
    public function export(Request $request,$id)
    {
        $pick = WarehousePick::where('id',$id)->first();
        $ids = explode(',',$pick->pick_ids);
        $order = Order::with('order_info','inventory')->whereIn('id',$ids)->get();

        return Excel::download(new OrderExport($order), $id.'_拣货单导出'.date('y-m-d H_i_s').'.xlsx');

    }


    /*
     * 出库
     */
    public function out(Request $request, $id){




    }




}
