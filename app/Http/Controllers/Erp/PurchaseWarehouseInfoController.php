<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseWarehouseInfoRequest;
use App\Models\Inventory;
use App\Models\Problem;
use App\Models\PurchaseWarehouseInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PurchaseWarehouseInfoController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = PurchaseWarehouseInfo::find($id);
        return view('erp.purchase_warehouse_info.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PurchaseWarehouseInfoRequest $request, $id)
    {
        //更新
        $result = PurchaseWarehouseInfo::find($id);
        $result->real_num = $request->real_num;
        $result->goods_text = $request->goods_text;
        return $result->save()?'0':'1';
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
     *标记问题订单
     */
    public function problem($id){
        $result = PurchaseWarehouseInfo::find($id);
        $problemArr = [
            'type_id'=>0,
            'order_type'=>2,   //入库
            'relate_id'=>$id,
            'problem_text'=>'采购入库问题订单',
            'problem_status'=>0,
            'created_at'=>Carbon::now()
        ];
        Problem::create($problemArr);
        $result->status = 4;
        return $result->save() ? '0' : '1';
    }





}
