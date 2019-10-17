<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductGoods;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        $category = (new Category())->group();
        $brand = Brand::where('brand_status','1')->get();
        $supplier = Supplier::where('supplier_status','1')->get();
        return view('erp.product.create', compact('category','brand','supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request);
        //存储表单信息
        $arr = [
            'product_name' => $request->product_name,
            'product_english' => $request->product_english,
            //'spu_id' => $spuId,
            'category_id' => $request->category_id,
            'type_id' => $request->type_id,
            'brand_id' => $request->brand_id,
            'product_code' => '',
            'product_barcode' => $request->product_barcode,
            'product_cost_price' => $request->product_cost_price,
            'product_price' => $request->product_price,
            'product_freight' => $request->product_freight,
            'product_size' => $request->product_size,
            'product_weight' => $request->product_weight,
            'product_image' => $request->product_image,
            'product_content' => $request->product_content,
            'supplier_id' => $request->supplier_id,
            'supplier_bid' => $request->supplier_bid,
            'supplier_url' => $request->supplier_url,
            'supplier_burl' => $request->supplier_burl,
            'product_commend' => $request->product_commend,
            'product_status' => $request->product_status,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        $lastId = DB::table('product')->insertGetId($arr);

        //$result = ProductGoods::insert($skuArr);
        return $lastId ? '0' : '1';


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
}
