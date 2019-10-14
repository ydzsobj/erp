<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        return view('erp.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandRequest $request)
    {
        //存储表单信息
        $result = Brand::insert([
            'brand_name'=>$request->brand_name,
            'brand_profile'=>$request->brand_profile,
            'brand_pic'=>$request->brand_pic,
            'brand_status'=>$request->brand_status,
            'brand_sort'=>$request->brand_sort,
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
        //修改操作
        $data = Brand::find($id);
        return view('erp.brand.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BrandRequest $request, $id)
    {
        //更新操作
        $result = Brand::find($id);
        $result->brand_name = $request->brand_name;
        $result->brand_profile = $request->brand_profile;
        $result->brand_pic = $request->brand_pic;
        $result->brand_status = $request->brand_status;
        $result->brand_sort = $request->brand_sort;
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
}
