<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.warehouse.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        return view('erp.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseRequest $request)
    {
        //存储表单信息
        $result = Warehouse::insert([
            'warehouse_name'=>$request->warehouse_name,
            'warehouse_address'=>$request->warehouse_address,
            'warehouse_code'=>$request->warehouse_code,
            'warehouse_status'=>$request->warehouse_status,
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);
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
        $data = Warehouse::find($id);
        return view('erp.warehouse.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseRequest $request, $id)
    {
        //更新操作
        $result = Warehouse::find($id);
        $result->warehouse_name = $request->warehouse_name;
        $result->warehouse_code = $request->warehouse_code;
        $result->warehouse_address = $request->warehouse_address;
        $result->warehouse_status = $request->warehouse_status;
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
        //删除操作
        $result = Warehouse::find($id);
        return $result->delete()?'0':'1';
    }
}
