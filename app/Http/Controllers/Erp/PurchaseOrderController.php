<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
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
        //
        dd($request);
        //存储表单信息
        $arr = [
            'purchase_order_code' => '',
            'purchase_type' => $request->purchase_type,
            'purchase_num' => $request->category_id,
            'purchase_money' => $request->category_id,
            'purchase_tax' => $request->category_id,
            'supplier_id' => $request->category_id,
            'supplier_contacts' => $request->category_id,
            'supplier_phone' => $request->category_id,
            'supplier_fax' => $request->category_id,
            'user_id' => $request->category_id,
            'purchase_text' => $request->category_id,
            'purchase_order_status' => '1',
            'created_at' => date('Y-m-d H:i:s', time()),
        ];




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

}
