<?php

namespace App\Http\Controllers\Erp;

use App\Events\OrderAuditSuccessed;
use App\Http\Controllers\CommonController;
use App\Http\Requests\OrderRequest;
use App\Imports\OrderImport;
use App\Imports\OrdersImport;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportOrderRequest;
use App\Models\Order;
use App\Models\OrderAuditLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends CommonController
{

    public function index()
    {

        return view('erp.order.index');
    }

    public function index2(Request $request)
    {
        $countries = config('order.country_list');
        $status_list = config('order.status_list');
        return view('erp.order.index2', compact('countries','status_list'));
    }

    public function create_import()
    {
        return view('erp.order.import');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('erp.order.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        //
        $filename = $request->upload_file;
        $import = new OrderImport();

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

    //订单列表查询
    public function list()
    {
        return view('erp.order.list');
    }

    //订单导入
    public function import()
    {
        return view('erp.order.import');
    }

    /**
     * @批量匹配库存
     */
    public function match(Request $request){
        $ids = $request->get('ids');
        $ids=explode(',',$ids);

        //匹配处理
        //$order = Order::whereIn('id',$ids)->get();

        $data = [
            'checked_at' => Carbon::now(),
            'order_status' => 1,
            'checked_id' => Auth::user()->id
        ];

        foreach ($ids as $key=>$value){
            if(empty($value)) continue;

            //$num = $this->doOrder($value);dd($num);
            $orderLogArr[] = [
                'order_id' => intval($value),
                'user_id' =>  Auth::guard('admin')->user()->id,
                'order_status' => 1,
                'order_text' => '订单已处理',
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
        }

        OrderLog::insert($orderLogArr);    //订单日志记录

        $res = Order::whereIn('id', $ids)->update($data);
        return $res ? '0':'1';
    }

    /**
     * @批量生成汇总单
     */
    public function createOrderPool(Request $request){
        $ids = $request->get('ids');
        $ids=explode(',',$ids);
        $data = [
            'checked_at' => Carbon::now(),
            'order_status' => 2,
            'checked_id' => Auth::user()->id
        ];

        foreach ($ids as $key=>$value){
            if(empty($value)) continue;
            $orderLogArr[$key] = [
                'order_id' => intval($value),
                'user_id' =>  Auth::guard('admin')->user()->id,
                'order_status' => 2,
                'order_text' => '订单已生成汇总单',
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
        }

        OrderLog::insert($orderLogArr);    //订单日志记录

        $res = Order::whereIn('id', $ids)->update($data);
        return $res ? '0':'1';
    }

    /*
    public function import(ImportOrderRequest $request){
        $file_path = $request->post('path');
        $country_id = $request->post('country_id');
        Excel::import(new OrdersImport($country_id), str_replace('storage','',$file_path), 'public');
    }
    */

    /*
     * 查看采购汇总单
     */
    public function orderPool(Request $request){
        if($request->method('GET')){
            return view('erp.order.order_pool');
        }
    }

    /**
     * 审核、取消订单
     */
    public function audit(Request $request, $id){

        $action = $request->get('action');

        $order = Order::find($id);

        $order->last_audited_at = Carbon::now();

        $order->audited_admin_id = Auth::user()->id;

        if($action == 'cancel_order'){
            $order->status = Order::STATUS_CANCELLED;
            $remark = '取消订单';
        }else{
            $order->status = Order::STATUS_AUDITED;
            $remark = '审核通过';
        }

        $res = $order->save();

        if($res){
            event(new OrderAuditSuccessed([$order->id], $remark ?? ''));
        }

        $msg = $res ? '设置成功':'设置失败';

        return returned($res, $msg, $order);

    }

    /**
     * @批量审核
     */
    public function batch_audit(Request $request){

        $order_ids = $request->post('order_ids');

        $data = [
            'last_audited_at' => Carbon::now(),
            'status' => Order::STATUS_AUDITED,
            'audited_admin_id' => Auth::user()->id
        ];

        $res = Order::whereIn('id', $order_ids)->update($data);

        if($res){
            event(new OrderAuditSuccessed($order_ids, '审核通过'));
        }

        $msg = $res ? '设置成功':'设置失败';

        return returned($res, $msg, $order_ids);
    }
}
