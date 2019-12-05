<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\OrderInfo;
use App\Models\PurchaseOrderInfo;
use App\Models\PurchaseOrderTrace;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasePoolController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.purchase_pool.index');
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
        return view('erp.purchase_pool.create',compact('supplier'));
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
                $plan_num = $value['plan_num']??0;
                $order_num = $value['order_num']??0;
                $infoArr[$key]['purchase_order_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['goods_name'];
                $infoArr[$key]['goods_attr_name'] = $value['goods_attr_name'];
                $infoArr[$key]['goods_attr_value'] = $value['goods_attr_value'];
                $infoArr[$key]['plan_num'] = $plan_num;
                $infoArr[$key]['order_num'] = $order_num;
                $infoArr[$key]['goods_num'] = $plan_num+$order_num;
                $infoArr[$key]['goods_money'] = $value['goods_money']??0;


                //$goods_money = $value['goods_num']*$value['goods_price'];
                //$tax_rate = isset($value['tax_rate'])?:0;
                //$tax = $goods_money*$tax_rate;
                //$infoArr[$key]['goods_price'] = $value['goods_price'];
                //$infoArr[$key]['tax_rate'] = $tax_rate;
                //$infoArr[$key]['tax'] = $tax;
                //$infoArr[$key]['money_tax'] = $goods_money + $tax;

                //订单详情商品采购状态
                $ids=explode(',',$value['ids']);
                OrderInfo::whereIn('id',$ids)->update(['goods_status'=>'1']);

            }
        }

        $purchase_order_trace = [
            'purchase_order_id' => $lastId,
            'purchase_order_log' => '采购订单创建成功！',
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        PurchaseOrderTrace::create($purchase_order_trace);   //采购订单轨迹

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
        //显示
        //$data = Inventory::with('warehouse')->where('goods_sku',$id)->get();
        //dd($data);

        return view('erp.purchase_pool.show',compact('id'));
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
}
