<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\OrderLog;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderTrace;
use App\Models\PurchaseWarehouse;
use App\Models\WarehousePick;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CommonController extends Controller
{

    //处理密码
    public function doPassword($password)
    {
        if ($password) {
            $password = preg_replace("/^(.{" . round(strlen($password) / 4) . "})(.+?)(.{" . round(strlen($password) / 6) . "})$/s", "\\1***\\3", $password);
            return $password;
        }
    }

    //写入登录日志
    public function admin_log($username, $password, $status, $admin_ip, $todo, $message)
    {
        $password = $this->doPassword($password);
        AdminLog::create(['username' => $username, 'admin_ip' => $admin_ip, 'status' => $status, 'password' => $password, 'todo' => $todo, 'message' => $message]);
    }

    //读取Excel数据
    public function load_excel(Excel $excel,$filename)
    {
        $filePath = 'storage/app/' . $filename;
        $reader = $excel->load($filePath);
        $reader = $reader->getSheet(0);

        return $reader->toArray();
    }

    /*
     * 订单碰库存
     */
    public function doOrder($orderId){
        $order = Order::with('order_info')->where('id',$orderId)->first();
        $warehouse_ids = $this->checkCurrency($order->order_currency);

        foreach($order->order_info as $key=>$value){
            $match = $this->matchInventory($warehouse_ids,$value['goods_sku'],$value['goods_num']);
            if($match['code']=='1'){
                //这里不涉及一单多品
                $value->where('id',$value['id'])->update(['goods_used'=>1,'goods_lock'=>1,'warehouse_id'=>$match['warehouse_id']]);
                $order->update(['order_lock'=>1,'order_used'=>1,'order_status'=>4,'warehouse_id'=>$match['warehouse_id']]);
                return ['order_status'=>4,'order_text'=>'订单已锁库'];
            }elseif($match['code']=='2'){
                $value->where('id',$value['id'])->update(['goods_used'=>1,'warehouse_id'=>$match['warehouse_id']]);
                $order->update(['order_used'=>1,'order_status'=>'3','warehouse_id'=>$match['warehouse_id']]);
                return ['order_status'=>3,'order_text'=>'订单未锁库，占用等待中'];
            }else{
                $order->update(['order_status'=>'1']);
                return ['order_status'=>1,'order_text'=>'订单已处理'];
            }
        }


    }

    /*
     * 锁定下单订单 (库存碰订单)
     */
    public function lockOrder($warehouse_id,$goods_sku,$order_num){
        $order_info = OrderInfo::with('order')->where(function ($query) use ($warehouse_id,$goods_sku){
            $query->where('goods_used',0)->where('goods_lock',0)->where('warehouse_id',0)->where('goods_status',1)->where('goods_sku',$goods_sku);
        })->limit($order_num)->orderBy('id','asc')->get();

        if(!$order_info->isEmpty()) $this->doLock($order_info,$warehouse_id);
    }

    /*
     * 锁定备货订单 (库存碰订单)
     */
    public function lockUsed($warehouse_id,$goods_sku,$plan_num){
        $order_info = OrderInfo::with('order')->where(function ($query) use ($warehouse_id,$goods_sku){
            $query->where('goods_used',1)->where('goods_lock',0)->where('warehouse_id',$warehouse_id)->where('goods_sku',$goods_sku);
        })->limit($plan_num)->orderBy('id','asc')->get();

        if(!$order_info->isEmpty()) $this->doLock($order_info,$warehouse_id);
    }

    /*
     * 处理锁定
     */
    public function doLock($order_info,$warehouse_id){
        $order_info_ids = [];$order_ids = [];
        foreach($order_info as $key=>$value){

            array_push($order_info_ids,$value['id']);
            array_push($order_ids,$value['order_id']);
            $orderLogArr[$key] = [
                'order_id' => $value['order_id'],
                'user_id' =>  Auth::guard('admin')->user()->id,
                'order_status' => 4,
                'order_text' => '订单已锁库',
                'created_at' => Carbon::now(),
            ];
        }
        $order_info_data = [
            'goods_used'=>1,
            'goods_lock'=>1,
            'warehouse_id'=>$warehouse_id,
        ];
        $order_data = [
            'order_status'=>4,
            'order_lock'=>1,
            'order_used'=>1,
            'warehouse_id'=>$warehouse_id,
        ];

        OrderInfo::whereIn('id', $order_info_ids)->update($order_info_data);
        Order::whereIn('id', $order_ids)->update($order_data);
        OrderLog::insert($orderLogArr);    //订单日志记录
    }




    /*
     * 处理库存数量
     */
    public function matchInventory($warehouse_ids,$goods_sku,$goods_num){
        $inventory = Inventory::where(function ($query) use($warehouse_ids,$goods_sku){
            $query->whereIn('warehouse_id',$warehouse_ids)->where('goods_sku',$goods_sku);
        })->get();

        foreach($inventory as $key=>$value){
            if($value['stock_unused_num']>=$goods_num){
                $value->stock_used_num += $goods_num;
                $value->stock_unused_num -= $goods_num;
                $result = $value->save();
                if($result){
                    return [
                        'code'=>'1',   //库存占用 库存锁定
                        'warehouse_id'=>$value['warehouse_id'],
                    ];
                }

            }elseif($value['warehouse_id']==1 && $value['plan_unused_num']>=$goods_num){
                $value->plan_used_num += $goods_num;
                $value->plan_unused_num -= $goods_num;
                $result = $value->save();
                if($result){
                    return [
                        'code'=>'2',   //在途备货占用 库存未锁定
                        'warehouse_id'=>$value['warehouse_id'],
                    ];
                }
            }


        }



        //dd($inventory);
    }

    /*
     *检查国家标识
     */
    public function checkCurrency($order_currency){
        switch ($order_currency){
            case 'IDR' :    //印尼
                return [3,4,1];
                break;
            case 'PHP' :    //菲律宾
                return [5,6,1];
                break;
            default :
                return [1];
                break;
        }
    }


    /*
     * 采购订单轨迹记录
     */
    public function purchaseOrderLog($id,$msg){
        PurchaseOrderTrace::create([
            'purchase_order_id'=>$id,
            'purchase_order_log'=>$msg,
            'created_at' => date('Y-m-d H:i:s', time())
        ]);
    }

    /*
     * 创建采购订单编号  8位  分类ID(2位)+年份(2位)+分类商品数量(4位)
     */
    public function createPurchaseOrderCode($code){

        $ymd = substr(date('Ymd'),2);
        $codeLength = 5;
        $codeStr = strtoupper($code);
        $purchase = PurchaseOrder::Where('purchase_order_code','like','%'.$ymd.'%')->orderBy('id','desc')->first();
        $purchaseOrder = $purchase['purchase_order_code'];
        $subCode = str_pad('1',$codeLength,'0',STR_PAD_LEFT);
        if ($purchaseOrder) {
            if(strstr($purchaseOrder,$codeStr)){
                $num = substr($purchaseOrder,1);
            }else{
                $num = $purchaseOrder;
            }
            $number = intval(substr($num,strlen($ymd))) + 1;
            $subCode = str_pad($number,$codeLength,'0',STR_PAD_LEFT);
        }
        return $codeStr . $ymd . $subCode;
    }


    /*
     * 创建采购入库编号  8位  分类ID(2位)+年份(2位)+分类商品数量(4位)
     */
    public function createPurchaseWarehouseCode($code){
        $ymd = substr(date('Ymd'),2);
        $codeLength = 5;
        $codeStr = strtoupper($code);
        $purchase = PurchaseWarehouse::Where('purchase_warehouse_code','like','%'.$ymd.'%')->orderBy('id','desc')->first();
        $purchaseWarehouse = $purchase['purchase_warehouse_code'];
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

    /*
     * 创建出库拣货编号  8位  分类ID(2位)+年份(2位)+分类商品数量(4位)
     */
    public function createWarehousePickCode($code){
        $ymd = substr(date('Ymd'),2);
        $codeLength = 5;
        $codeStr = strtoupper($code);
        $pick = WarehousePick::Where('pick_code','like','%'.$ymd.'%')->orderBy('id','desc')->first();
        $warehousePick = $pick['pick_code'];
        $subCode = str_pad('1',$codeLength,'0',STR_PAD_LEFT);
        if ($warehousePick) {
            if(strstr($warehousePick,$codeStr)){
                $code = substr($warehousePick,1);
            }else{
                $code = $warehousePick;
            }
            $number = intval(substr($code,strlen($ymd))) + 1;
            $subCode = str_pad($number,$codeLength,'0',STR_PAD_LEFT);
        }
        return $codeStr . $ymd . $subCode;
    }


}
