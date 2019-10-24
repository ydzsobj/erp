<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseWarehouse;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.purchase_warehouse.index');
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
        $warehouse = Warehouse::where('warehouse_status','1')->get();
        return view('erp.purchase_warehouse.create',compact('supplier','warehouse'));
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
        $purchase_warehouse_code = $this->createPurchaseWarehouseCode();
        $arr = [
            'purchase_order_code' => $purchase_warehouse_code,
            'payment_type' => $request->payment_type,
            'purchase_num' => $request->purchase_num,
            'purchase_money' => $request->purchase_money,
            'purchase_tax' => $request->purchase_tax,
            'supplier_id' => $request->supplier_id,
            'supplier_contacts' => $request->supplier_contacts,
            'supplier_phone' => $request->supplier_phone,
            'supplier_fax' => $request->supplier_fax,
            'user_id' => Auth::guard('admin')->user()->id,
            'purchase_text' => $request->purchase_text,
            'deliver_at' => $request->deliver_at,
            'purchase_order_status' => '0',
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        //$lastId = DB::table('purchase_warehouse')->insertGetId($arr);
        $lastId = 33;
        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                $infoArr[$key]['purchase_warehouse_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['sku_name'];
                $infoArr[$key]['goods_attr_name'] = $value['goods_attr_name'];
                $infoArr[$key]['goods_attr_value'] = $value['goods_attr_value'];
                $infoArr[$key]['goods_price'] = $value['goods_price'];
                $infoArr[$key]['goods_num'] = $value['goods_num'];
                $infoArr[$key]['goods_money'] = $value['goods_money'];
                $infoArr[$key]['tax_rate'] = $value['tax_rate'];
                $infoArr[$key]['tax'] = $value['tax'];
                $infoArr[$key]['money_tax'] = $value['money_tax'];
            }
        }dd($infoArr);
        $result = PurchaseWarehouseInfo::insert($infoArr);
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
     * 创建SPU编号  8位  分类ID(2位)+年份(2位)+分类商品数量(4位)
     */
    public function createPurchaseWarehouseCode(){
        $purchase = PurchaseWarehouse::orderBy('id','desc')->first();
        $purchaseWarehouse = $purchase['purchase_warehouse_code'];
        $ymd = substr(date('Ymd'),2);
        $codeLength = 5;
        $codeStr = 'R';
        $subCode = str_pad('1',$codeLength,'0',STR_PAD_LEFT);
        if ($purchaseWarehouse) {
            if(strstr($purchaseWarehouse,$codeStr)){
                $code = substr($purchaseWarehouse,1);
            }else{
                $code = $purchaseWarehouse;
            }
            $number = intval(substr($code,strlen($ymd))) + 1;
            $subCode = str_pad($number,$codeLength,'0',STR_PAD_LEFT);
        }
        return $codeStr . $ymd . $subCode;
    }



}
