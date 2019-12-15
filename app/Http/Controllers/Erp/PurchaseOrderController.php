<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\InventoryInfo;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInfo;
use App\Models\PurchaseOrderTrace;
use App\Models\Supplier;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.purchase_order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        $supplier = Supplier::where('supplier_status','1')->get();
        return view('erp.purchase_order.create',compact('supplier'));
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
        $purchase_order_code = $this->createPurchaseOrderCode('C');
        $arr = [
            'purchase_order_code' => $purchase_order_code,
            'payment_type' => $request->payment_type,
            'purchase_num' => isset($request->purchase_num)?$request->purchase_num:0,
            'purchase_money' => isset($request->purchase_money)?$request->purchase_money:0,
            'purchase_tax' => isset($request->purchase_tax)?$request->purchase_tax:0,
            'money_tax' => isset($request->money_tax)?$request->money_tax:0,
            'supplier_id' => $request->supplier_id,
            'user_id' => Auth::guard('admin')->user()->id,
            'purchase_text' => $request->purchase_text,
            'expect_out_at' => $request->expect_out_at,
            'expect_deliver_at' => $request->expect_deliver_at,
            'purchase_order_status' => '0',
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        $lastId = DB::table('purchase_order')->insertGetId($arr);

        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                $plan_num = $value['goods_num']??0;
                $infoArr[$key]['purchase_order_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['sku_name'];
                $infoArr[$key]['goods_attr_name'] = $value['goods_attr_name'];
                $infoArr[$key]['goods_attr_value'] = $value['goods_attr_value'];
                $infoArr[$key]['goods_money'] = $value['goods_money'];
                $infoArr[$key]['plan_num'] = $plan_num;
                $infoArr[$key]['goods_num'] = $plan_num;

                /*$goods_money = $value['goods_num']*$value['goods_price'];
                $tax = $goods_money*$value['tax_rate'];
                $infoArr[$key]['goods_money'] = $goods_money;
                $infoArr[$key]['tax_rate'] = $value['tax_rate'];
                $infoArr[$key]['tax'] = $tax;
                $infoArr[$key]['money_tax'] = $goods_money + $tax;*/

            }
        }

        $this->purchaseOrderLog($lastId,'采购订单创建成功！');
        $result = PurchaseOrderInfo::insert($infoArr);

        return $result ?'0':'1';

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //展示操作
        $supplier = Supplier::where('supplier_status','1')->get();
        $warehouse = Warehouse::where('warehouse_status','1')->get();
        return view('erp.purchase_order.show',compact('supplier','warehouse','id'));
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


    public function show_goods(){
        return view('erp.purchase_order.show_goods');
    }


    /*
     *审核
     */
    public function check(Request $request, $id){
        $result = PurchaseOrder::find($id);
        $result->purchase_order_status = 1;
        $result->checked_id = Auth::guard('admin')->user()->id;
        $result->checked_at = date('Y-m-d H:i:s', time());

        $this->purchaseOrderLog($id,'采购订单已审核！');

        return $result->save()?'0':'1';
    }

    /*
     * 出货时间
     */
    public function time(Request $request, $id){
        $result = PurchaseOrder::find($id);
        $result->purchase_order_status = 3;
        $result->out_at = date('Y-m-d H:i:s', time());

        $this->purchaseOrderLog($id,'供应商已出货！');

        return $result->save()?'0':'1';
    }



}
