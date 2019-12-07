<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderTrace;
use App\Models\PurchaseWarehouse;
use App\Models\WarehousePick;
use Illuminate\Http\Request;
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
            dd($match);
        }

    }

    /*
     * 库存碰订单
     */


    /*
     * 处理库存数量
     */
    public function matchInventory($warehouse_ids,$goods_sku,$goods_num){
        $inventory = Inventory::where(function ($query) use($warehouse_ids,$goods_sku){
            $query->whereIn('warehouse_id',$warehouse_ids)->where('goods_sku',$goods_sku);
        })->get();
        dd($inventory);
    }

    /*
     *检查国家标识
     */
    public function checkCurrency($order_currency){
        switch ($order_currency){
            case 'IDR' :    //印尼
                return [2,1];
                break;
            case 'PHP' :    //菲律宾
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
