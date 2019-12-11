<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

            $order = Order::with(['order_info','inventory'=>function($query){
                $query->where('warehouse_id','=','1');
            }])->where(function ($query) use ($value,$warehouse_id){
                $query->where('id',$value)->where('order_status','4')->where('order_lock','1')->where('warehouse_id',$warehouse_id);
            })->first();

dd($order['inventory']);
            dd($order);

        }

        OrderLog::insert($orderLogArr);    //订单日志记录

    }






}
