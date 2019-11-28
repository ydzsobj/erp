<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\PurchaseOrder;
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
