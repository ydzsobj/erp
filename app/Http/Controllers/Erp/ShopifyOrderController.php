<?php

namespace App\Http\Controllers\Erp;

use App\Events\OrderAuditSuccessed;
use App\Exports\OrdersExport;
use App\Imports\OrdersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuditRequest;
use App\Http\Requests\ImportOrderRequest;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\ShopifyOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Excel;

class ShopifyOrderController extends Controller
{

    public function index(Request $request)
    {
        $countries = config('order.country_list');
        $status_list = config('order.status_list');
        $audit_status_options = ShopifyOrder::audit_status_options();
        return view('erp.shopify_order.index', compact(
                'countries',
                'status_list',
                'audit_status_options'
            )
        );
    }

    public function create_audit(Request $request, $id)
    {
        $detail = ShopifyOrder::find($id);
        $audit_status_options = ShopifyOrder::audit_status_options();
        return view('erp.shopify_order.create_audit', compact('audit_status_options','detail'));
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
        $status_list = config('order.status_list');
        $order = new ShopifyOrder();
        $detail = $order->detail($id);
        return view('erp.shopify_order.edit', compact('detail', 'status_list'));
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
        $req = $request->except('_token');

        $result = ShopifyOrder::where('id', $id)->update($req);

        $msg = $result ? '设置成功':'设置失败';

        return returned($result, $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $res = ShopifyOrder::where('id', $id)->delete();
        $msg = $res ? '设置成功':'设置失败';

        return returned($res, $msg);
    }

    /**
     * 审核、取消订单
     */
    public function audit(Request $request, $id){

        $status = $request->post('status');
        $remark = $request->post('remark');

        $order = ShopifyOrder::find($id);

        $order->last_audited_at = Carbon::now();

        $order->audited_admin_id = Auth::user()->id;

        $order->status = $status;

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
    public function batch_audit(AuditRequest $request){

        $order_ids = $request->post('order_ids');
        $status = $request->post('status');
        $remark = $request->post('remark');

        $data = [
            'last_audited_at' => Carbon::now(),
            'status' => $status,
            'audited_admin_id' => Auth::user()->id
        ];

        $res = ShopifyOrder::whereIn('id', $order_ids)->update($data);

        if($res){
            event(new OrderAuditSuccessed($order_ids, $remark));
        }

        $msg = $res ? '设置成功':'设置失败';

        return returned($res, $msg, $order_ids);
    }

    //订单导出
    public function export(Request $request){

        $o = new ShopifyOrder();
        $data = $o->export($request);
        ob_end_clean();
        ob_start();
        // dd($data);
        return Excel::download(new OrdersExport($data), '订单导出'.date('y-m-d H_i_s').'.xlsx');
    }

    //客服备注
    public function update_remark(Request $request, $id){

        $remark = $request->post('remark');

        $shopify_order = ShopifyOrder::find($id);

        $shopify_order->remark = $remark;

        $res = $shopify_order->save();

        $msg = $res ? '设置成功':'设置失败';

        return returned($res, $msg);
    }



}
