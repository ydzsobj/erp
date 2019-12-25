<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseWarehouseRequest;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use App\Models\OrderInfo;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderWarehouse;
use App\Models\PurchaseWarehouse;
use App\Models\PurchaseWarehouseInfo;
use App\Models\Supplier;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseWarehouseController extends CommonController
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
    public function store(PurchaseWarehouseRequest $request)
    {
        //dd($request->table);
        //存储表单信息
        $purchase_warehouse_code = $this->createPurchaseWarehouseCode('R');
        //$purchase_order_id = $request->purchase_order_id;
        $arr = [
            'purchase_warehouse_code' => $purchase_warehouse_code,
            'payment_type' => $request->payment_type,
            'purchase_num' => isset($request->purchase_num)?$request->purchase_num:0,
            'purchase_money' => isset($request->purchase_money)?$request->purchase_money:0,
            'supplier_id' => $request->supplier_id,
            'warehouse_id' => $request->warehouse_id,
            'user_id' => Auth::guard('admin')->user()->id,
            'warehouse_text' => $request->warehouse_text,
            'stored_at' => $request->stored_at,
            'purchase_warehouse_status' => '0',
            'created_at' => date('Y-m-d H:i:s', time()),

            //'purchase_tax' => isset($request->purchase_tax)?$request->purchase_tax:0,
            //'money_tax' => isset($request->money_tax)?$request->money_tax:0,
        ];

        $lastId = DB::table('purchase_warehouse')->insertGetId($arr);

       /* if(isset($purchase_order_id)){
            PurchaseOrderWarehouse::create([
                'purchase_order_id'=>$purchase_order_id,
                'purchase_warehouse_id'=>$lastId
            ]);
            PurchaseOrder::where('id',$purchase_order_id)->update(['purchase_order_status'=>'3']);
            $this->purchaseOrderLog($purchase_order_id,'入库订单已生成！');
        }*/

        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                //采购入库
                $infoArr[$key]['purchase_warehouse_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['sku_name'];
                $infoArr[$key]['goods_attr_name'] = $value['goods_attr_name'];
                $infoArr[$key]['goods_attr_value'] = $value['goods_attr_value'];
                //$infoArr[$key]['goods_price'] = $value['goods_price'];
                $infoArr[$key]['goods_num'] = $value['goods_num'];
                //$infoArr[$key]['order_num'] = $value['order_num'];
                $infoArr[$key]['plan_num'] = $value['goods_num'];
                $infoArr[$key]['goods_money'] = $value['goods_money'];
                $infoArr[$key]['created_at'] = date('Y-m-d H:i:s', time());

//                $infoArr[$key]['tax_rate'] = $value['tax_rate'];
//                $infoArr[$key]['tax'] = $tax;
//                $infoArr[$key]['money_tax'] = $goods_money+$tax;

                //库存
                $inventoryArr['goods_id'] = $value['id'];
                $inventoryArr['goods_sku'] = $value['goods_sku'];
                $inventoryArr['afloat_num'] = $value['goods_num'];
                //$inventoryArr['order_num'] = $value['order_num'];
                $inventoryArr['plan_num'] = $value['goods_num'];
                $inventoryArr['plan_unused_num'] = $value['goods_num'];
                $inventoryArr['warehouse_id'] = $request->warehouse_id;
                $inventoryArr['created_at'] = date('Y-m-d H:i:s', time());

                //$inventoryArr['afloat_price'] = $value['goods_price'];
                //['afloat_money'] = $goods_money;

                $inventory = Inventory::where(['goods_sku'=>$value['goods_sku'],'warehouse_id'=>$request->warehouse_id])->first();
                if($inventory){
                    $inventory->afloat_num = $inventory->afloat_num + $value['goods_num'];
                    //$inventory->order_num = $inventory->order_num + $value['order_num'];
                    $inventory->plan_num = $inventory->plan_num + $value['goods_num'];
                    $inventory->plan_unused_num = $inventory->plan_unused_num + $value['goods_num'];
                    $inventory->save();
                }else{
                    Inventory::insert($inventoryArr);
                }

            }
        }


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
        return view('erp.purchase_warehouse.show',compact('id'));
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
    *审核
    */
    public function check(Request $request, $id){
        $result = PurchaseWarehouse::with('purchase_order_warehouse')->where('id',$id)->first();
        $result->purchase_warehouse_status = 1;
        $result->checked_id = Auth::guard('admin')->user()->id;
        $result->checked_at = Carbon::now();
        if(isset($result['purchase_order_warehouse'])){
            PurchaseOrder::where('id',$result['purchase_order_warehouse']->purchase_order_id)->update(['purchase_order_status'=>4,'deliver_at'=>Carbon::now()]);
            $this->purchaseOrderLog($result->purchase_order_warehouse->purchase_order_id,'采购订单已到货！');
        }

        return $result->save()?'0':'1';
    }

    /*
     *
     */
    public function add(PurchaseWarehouseRequest $request)
    {
        //dd($request->table);
        //存储表单信息
        $purchase_warehouse_code = $this->createPurchaseWarehouseCode('R');
        $purchase_order_id = $request->purchase_order_id;
        $arr = [
            'purchase_warehouse_code' => $purchase_warehouse_code,
            'payment_type' => $request->payment_type,
            'purchase_num' => isset($request->purchase_num)?$request->purchase_num:0,
            'purchase_money' => isset($request->purchase_money)?$request->purchase_money:0,
            'supplier_id' => $request->supplier_id,
            'warehouse_id' => $request->warehouse_id,
            'user_id' => Auth::guard('admin')->user()->id,
            'warehouse_text' => $request->warehouse_text,
            'stored_at' => $request->stored_at,
            'purchase_warehouse_status' => '0',
            'created_at' => date('Y-m-d H:i:s', time()),

            //'purchase_tax' => isset($request->purchase_tax)?$request->purchase_tax:0,
            //'money_tax' => isset($request->money_tax)?$request->money_tax:0,
        ];

        $lastId = DB::table('purchase_warehouse')->insertGetId($arr);

        if(isset($purchase_order_id)){
            PurchaseOrderWarehouse::create([
                'purchase_order_id'=>$purchase_order_id,
                'purchase_warehouse_id'=>$lastId
            ]);
            PurchaseOrder::where('id',$purchase_order_id)->update(['purchase_order_status'=>'2']);
            $this->purchaseOrderLog($purchase_order_id,'入库订单已生成！');
        }

        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                //采购入库
                $infoArr[$key]['purchase_warehouse_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['goods_id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['goods_name'];
                $infoArr[$key]['goods_attr_name'] = $value['goods_attr_name'];
                $infoArr[$key]['goods_attr_value'] = $value['goods_attr_value'];
                $infoArr[$key]['goods_price'] = $value['goods_price'];
                $infoArr[$key]['goods_num'] = $value['goods_num'];
                $infoArr[$key]['order_num'] = $value['order_num'];
                $infoArr[$key]['plan_num'] = $value['plan_num'];
                $infoArr[$key]['goods_money'] = $value['goods_money'];
                $infoArr[$key]['created_at'] = date('Y-m-d H:i:s', time());

//                $infoArr[$key]['tax_rate'] = $value['tax_rate'];
//                $infoArr[$key]['tax'] = $tax;
//                $infoArr[$key]['money_tax'] = $goods_money+$tax;

                //库存
                $inventoryArr['goods_id'] = $value['goods_id'];
                $inventoryArr['goods_sku'] = $value['goods_sku'];
                $inventoryArr['afloat_num'] = $value['goods_num'];
                $inventoryArr['order_num'] = $value['order_num'];
                $inventoryArr['plan_num'] = $value['plan_num'];
                $inventoryArr['plan_unused_num'] = $value['plan_num'];
                $inventoryArr['warehouse_id'] = $request->warehouse_id;
                $inventoryArr['created_at'] = date('Y-m-d H:i:s', time());

                //$inventoryArr['afloat_price'] = $value['goods_price'];
                //['afloat_money'] = $goods_money;

                $inventory = Inventory::where(['goods_sku'=>$value['goods_sku'],'warehouse_id'=>$request->warehouse_id])->first();
                if($inventory){
                    $inventory->afloat_num = $inventory->afloat_num + $value['goods_num'];
                    $inventory->order_num = $inventory->order_num + $value['order_num'];
                    $inventory->plan_num = $inventory->plan_num + $value['plan_num'];
                    $inventory->plan_unused_num = $inventory->plan_unused_num + $value['plan_num'];
                    $inventory->save();
                }else{
                    Inventory::insert($inventoryArr);
                }

            }
        }


        $result = PurchaseWarehouseInfo::insert($infoArr);
        return $result ? '0' : '1';
    }


    //提交入库
    public function in(Request $request, $id){
        $warehouse = PurchaseWarehouse::where('id',$id)->first();
        $info = PurchaseWarehouseInfo::where('purchase_warehouse_id',$id)->orderBy('id','asc')->get();
        $inventoryInfoArr = [];$problemNum = 0;
        foreach($info as $key=>$value){
            if($value->status==4) {$problemNum++;continue;}
            if($value->status==1) {continue;}
            $inventory = Inventory::where(function ($query) use ($value,$warehouse){
                $query->where('goods_sku','=',$value['goods_sku'])->where('warehouse_id','=',$warehouse['warehouse_id']);
            })->first();

            $inventoryInfoArr[$key]['goods_id'] = $value['goods_id'];
            $inventoryInfoArr[$key]['warehouse_id'] = $warehouse['warehouse_id'];
            $inventoryInfoArr[$key]['goods_sku'] = $value['goods_sku'];
            $inventoryInfoArr[$key]['goods_name'] = $value['goods_name'];
            $inventoryInfoArr[$key]['in_num'] = $value['goods_num'];
            $inventoryInfoArr[$key]['in_money'] = $value['goods_money'];
            $inventoryInfoArr[$key]['stock_code'] = $warehouse['purchase_warehouse_code'];
            $inventoryInfoArr[$key]['stock_num'] = $value['goods_num']+$inventory['stock_num'];
            $inventoryInfoArr[$key]['created_at'] = date('Y-m-d H:i:s', time());
//            $inventoryInfoArr[$key]['stock_price'] = $value['goods_price'];
//            $inventoryInfoArr[$key]['in_price'] = $value['goods_price'];
//            $inventoryInfoArr[$key]['stock_money'] = $value['goods_money']+$inventory['stock_money'];


            $inventory->afloat_num = $inventory->afloat_num - $value['goods_num'];
            $inventory->stock_num = $inventory->stock_num + $value['goods_num'];
            $inventory->in_num = $inventory->in_num + $value['goods_num'];
            $inventory->plan_num = $inventory->plan_num - $value['plan_num'];
            $inventory->order_num = $inventory->order_num - $value['order_num'];


            if($inventory->plan_used_num>=$value['plan_num']){
                $inventory->stock_used_num = $inventory->stock_used_num + $value['order_num'] + $value['plan_num'];
                $inventory->plan_used_num = $inventory->plan_used_num - $value['plan_num'];
                $this->lockOrder($warehouse['warehouse_id'],$value['goods_sku'],$value['order_num']);   //锁定下单订单
                $this->lockUsed($warehouse['warehouse_id'],$value['goods_sku'],$value['plan_num']);    //锁定备货订单
            }else{
                if($inventory->plan_unused_num>=$value['plan_num']){
                    $inventory->stock_used_num = $inventory->stock_used_num + $value['order_num'];
                    $inventory->stock_unused_num = $inventory->stock_unused_num + $value['plan_num'];
                    $inventory->plan_unused_num = $inventory->plan_unused_num - $value['plan_num'];
                    $this->lockOrder($warehouse['warehouse_id'],$value['goods_sku'],$value['order_num']);   //锁定下单订单
                }else{
                    $balance = $value['plan_num'] - $inventory->plan_used_num;
                    $inventory->stock_used_num = $inventory->stock_used_num + $value['order_num'] + $inventory->plan_used_num;
                    $inventory->stock_unused_num = $inventory->stock_unused_num + $balance;
                    $inventory->plan_used_num = $inventory->plan_used_num - $inventory->plan_used_num;
                    $inventory->plan_unused_num = $inventory->plan_unused_num - $balance;
                    $this->lockOrder($warehouse['warehouse_id'],$value['goods_sku'],$value['order_num']);   //锁定下单订单
                    $this->lockUsed($warehouse['warehouse_id'],$value['goods_sku'],$inventory->plan_used_num);    //锁定备货订单
                }
            }


            $inventory->save();
            $value->status = 1;
            $value->save();
            //库存匹配订单


//            $inventory->afloat_price = $value['goods_price'];
//            $inventory->afloat_money = $inventory->afloat_money - $value['goods_money'];
//            $inventory->stock_price = $value['goods_price'];
//            $inventory->stock_money = $inventory->stock_money + $value['goods_money'];
//            $inventory->in_price = $value['goods_price'];
//            $inventory->in_money = $inventory->in_money + $value['goods_money'];



        }

        InventoryInfo::insert($inventoryInfoArr);


        if($problemNum>0){
            $warehouse->purchase_warehouse_status = 4;
        }else{
            $warehouse->purchase_warehouse_status = 2;
        }

        $warehouse->stored_id = Auth::guard('admin')->user()->id;
        $warehouse->stored_at = date('Y-m-d H:i:s', time());
        $result = $warehouse->save();

        return $result ? '0' : '1';

    }





}
