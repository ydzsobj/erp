<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use App\Models\PurchaseWarehouse;
use App\Models\PurchaseWarehouseInfo;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseIn;
use App\Models\WarehouseInInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseInController extends CommonController
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
        //创建操作
        $supplier = Supplier::where('supplier_status','1')->get();
        $warehouse = Warehouse::where('warehouse_status','1')->get();
        return view('erp.warehouse_in.create',compact('supplier','warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->table);
        //存储表单信息
        $warehouse_in_code = $this->createWarehouseInCode('I');

        $arr = [
            'warehouse_in_code' => $warehouse_in_code,
            'payment_type' => $request->payment_type,
            'supplier_id' => $request->supplier_id,
            'warehouse_id' => $request->warehouse_id,
            'user_id' => Auth::guard('admin')->user()->id,
            'warehouse_text' => $request->warehouse_text,
            'status' => 0,
            'created_at' => Carbon::now(),

        ];

        $lastId = DB::table('warehouse_in')->insertGetId($arr);


        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                //采购入库
                $attr_value = explode(',',$value['goods_attr_value']);
                $infoArr[$key]['warehouse_in_id'] = $lastId;
                $infoArr[$key]['goods_id'] = $value['id'];
                $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                $infoArr[$key]['goods_name'] = $value['sku_name'];
                $infoArr[$key]['goods_color'] = $attr_value[0];
                $infoArr[$key]['goods_size'] = $attr_value[1]??'';
                $infoArr[$key]['goods_num'] = $value['goods_num'];
                $infoArr[$key]['plan_num'] = $value['goods_num'];
                $infoArr[$key]['goods_money'] = $value['goods_money'];
                $infoArr[$key]['created_at'] = Carbon::now();

            }
        }


        $result = WarehouseInInfo::insert($infoArr);
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
        return view('erp.warehouse_in.show',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //更新操作
        $supplier = Supplier::where('supplier_status','1')->get();
        $warehouse = Warehouse::where('warehouse_status','1')->get();
        $data = PurchaseWarehouse::where('id',$id)->first();
        return view('erp.warehouse_in.edit',compact('supplier','warehouse','data'));


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
        //dd($request->table);

        if(isset($request->table)) {
            foreach ($request->table['dataTable'] as $key => $value) {
                if($value['real_num']>$value['balance_num']){
                    return response()->json(['code'=>1,'msg'=>'提交数量大于差额数量，多出商品数量请另行入库！']);
                }
            }
        }else{
            return response()->json(['code'=>1,'msg'=>'提交数据为空，请核实数据！']);
        }

        //事务处理
        $exception = DB::transaction(function () use ($request, $id) {
            //更新表单信息
            $warehouse_in_code = $this->createWarehouseInCode('I');
            $purchase_warehouse_id = $request->purchase_warehouse_id;
            $arr = [
                'warehouse_in_code' => $warehouse_in_code,
                'purchase_warehouse_id' => $purchase_warehouse_id,
                'payment_type' => $request->payment_type,
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'user_id' => Auth::guard('admin')->user()->id,
                'warehouse_text' => $request->warehouse_text,
                'status' => 0,
                'created_at' => Carbon::now(),

            ];

            $lastId = DB::table('warehouse_in')->insertGetId($arr);
            //$lastId = 111;
            $batch = 0;
            if (isset($request->table)) {
                foreach ($request->table['dataTable'] as $key => $value) {
                    //采购入库
                    $attr_value = explode(',', $value['goods_attr_value']);
                    $balance = $this->balance($value['balance_num'], $value['balance_order_num'], $value['balance_plan_num'], $value['real_num']);
                    $infoArr[$key]['warehouse_in_id'] = $lastId;
                    $infoArr[$key]['goods_id'] = $value['id'];
                    $infoArr[$key]['goods_sku'] = $value['goods_sku'];
                    $infoArr[$key]['goods_name'] = $value['goods_name'];
                    $infoArr[$key]['goods_color'] = $attr_value[0];
                    $infoArr[$key]['goods_size'] = $attr_value[1] ?? '';
                    $infoArr[$key]['goods_num'] = $value['real_num'];
                    $infoArr[$key]['order_num'] = $balance['order_num'];
                    $infoArr[$key]['plan_num'] = $balance['plan_num'];
                    $infoArr[$key]['goods_money'] = $value['goods_money'];
                    $infoArr[$key]['created_at'] = Carbon::now();

                    $purchaseWarehouseInfoArr = [
                        'balance_num' => $balance['balance_num'],
                        'balance_order_num' => $balance['balance_order_num'],
                        'balance_plan_num' => $balance['balance_plan_num'],
                    ];
                    if ($balance['balance_num'] > 0) {
                        $batch++;
                    }
                    PurchaseWarehouseInfo::where('id', $value['id'])->update($purchaseWarehouseInfoArr);

                }
            }

            if ($batch < 1) {
                PurchaseWarehouse::where('id', $purchase_warehouse_id)->update(['purchase_warehouse_status' => 2]);
            } else {
                PurchaseWarehouse::where('id', $purchase_warehouse_id)->update(['purchase_warehouse_status' => 4]);
            }

            $result = WarehouseInInfo::insert($infoArr);


        });

        return is_null($exception) ? '0' : '1';





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


    //平衡数据
    public function balance($balance_num,$balance_order_num,$balance_plan_num,$real_num){
        if($real_num>=$balance_num){
            $get_order_num = $balance_order_num;
            $get_plan_num = $real_num - $balance_order_num;
            $get_balance_num = 0;
            $get_balance_order_num = 0;
            $get_balance_plan_num = 0;
        }else{
            if($real_num>=$balance_order_num){
                $get_order_num = $balance_order_num;
                $get_plan_num = $real_num - $balance_order_num;
                $get_balance_num = $balance_num - $real_num;
                $get_balance_order_num = 0;
                $get_balance_plan_num = $balance_plan_num - $get_plan_num;
            }else{
                $get_order_num = $real_num;
                $get_plan_num = 0;
                $get_balance_num = $balance_num - $real_num;
                $get_balance_order_num = $balance_order_num - $real_num;
                $get_balance_plan_num = $balance_plan_num;
            }
        }

        return $balance =[
            'order_num'=>$get_order_num,
            'plan_num'=>$get_plan_num,
            'balance_num'=>$get_balance_num,
            'balance_order_num'=>$get_balance_order_num,
            'balance_plan_num'=>$get_balance_plan_num,
        ];
    }


    //提交入库
    public function in(Request $request, $id){
        //事务处理
        $exception = DB::transaction(function () use ($request,$id){

            $warehouse = WarehouseIn::where('id',$id)->first();
            if($warehouse->status == 1 ){return response()->json(['code'=>0,'msg'=>'已提交入库，请勿重复提交！']);}
            $info = WarehouseInInfo::where('warehouse_in_id',$id)->orderBy('id','asc')->get();
            $inventoryInfoArr = [];
            foreach($info as $key=>$value){
                if($value->status==1) {continue;}
                $inventory = Inventory::where(function ($query) use ($value,$warehouse){
                    $query->where('goods_sku','=',$value['goods_sku'])->where('warehouse_id','=',$warehouse['warehouse_id']);
                })->first();


                if($inventory){

                    $inventoryInfoArr[$key]['goods_id'] = $value['goods_id'];
                    $inventoryInfoArr[$key]['warehouse_id'] = $warehouse['warehouse_id'];
                    $inventoryInfoArr[$key]['goods_sku'] = $value['goods_sku'];
                    $inventoryInfoArr[$key]['goods_name'] = $value['goods_name'];
                    $inventoryInfoArr[$key]['in_num'] = $value['goods_num'];
                    $inventoryInfoArr[$key]['in_money'] = $value['goods_money'];
                    $inventoryInfoArr[$key]['stock_code'] = $warehouse['purchase_warehouse_code'];
                    $inventoryInfoArr[$key]['stock_num'] = $value['goods_num']+$inventory['stock_num'];
                    $inventoryInfoArr[$key]['created_at'] = Carbon::now();


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


                }

                $value->status = 1;
                $value->save();


            }

            InventoryInfo::insert($inventoryInfoArr);
            $warehouse->status = 1;
            $warehouse->stored_id = Auth::guard('admin')->user()->id;
            $warehouse->stored_at = Carbon::now();

            $warehouse->save();


        });


        return is_null($exception) ? '0' : '1';

    }




}
