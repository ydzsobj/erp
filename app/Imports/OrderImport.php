<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\OrderLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as FormatDate;

class OrderImport implements ToCollection
{

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
        $success = 0;
        $fail = 0;
        $exist = 0;
        $sum = 0;
        $order = new Order();



        foreach ($collection as $key => $row) {
            if ($key == 0) {
                continue;
            }
            $goods_sku = trim($row[12]);
            $order_sn = trim($row[2]);   //订单sn

            $order_data = $order->order_sn($order_sn);

            if ($order_data) {
                $order_id = $order_data->id;
                $exist++;
                $sum++;
                continue;
            }

            if(isset($order_id)){
                $order_info = OrderInfo::where(function ($query) use ($order_id,$goods_sku){
                    $query->where('order_id','=',$order_id)
                        ->where('goods_sku','=',$goods_sku);
                })->first();
                if ($order_info) {
                    continue;
                }
            }


            $excel_order = [
                'ordered_at' => $this->valid_date($row[0]) ? $row[0] : Carbon::parse(FormatDate::excelToDateTimeObject($row[0] ? $row[0] : '41039')),  //下单时间
                'order_checked_at' => $this->valid_date($row[1]) ? $row[1] : Carbon::parse(FormatDate::excelToDateTimeObject($row[1] ? $row[1] : '41039')),  //审核时间
                'order_sn' => trim($row[2]),  //订单SN
                'order_name' => trim($row[3]),  //收件人
                'order_code' => intval($row[4]),  //收件人邮编
                'order_phone' => trim($row[5]),  //收件人电话
                'order_province' => trim($row[6]),  //收件人省
                'order_city' => trim($row[7]),  //收件人市
                //'order_county' => $row[3],  //收件人县
                'order_area' => trim($row[8]),  //收件地区
                'order_address' => trim($row[9]),  //收件人详细地址
                'order_money' => floatval($row[10]),  //代收货款
                'order_currency' => trim($row[11]),  //币种
                'order_country' => trim($row[21]),  //目的国家
                'order_type' => '普通订单',  //订单类型
                'coustomer_text' => trim($row[22]),  //客服备注
                'order_from' => trim($row[11]),  //订单来源
                'created_at' => date('Y-m-d H:i:s', time()),
            ];


            //$data = Order::create($excel_order);
            if ($order_sn && $row[0]) {
                $lastId = DB::table('order')->insertGetId($excel_order);
                $sum++;
            }
            $orderLogArr[$key] = [
                'order_id' => $lastId,
                'user_id' =>  Auth::guard('admin')->user()->id,
                'order_status' => 0,
                'order_text' => '订单待处理',
                'created_at' => date('Y-m-d H:i:s', time()),
            ];

            $skuArr = [
                'goods_sku' => $goods_sku,
                'goods_num' => intval(trim($row[13])),
                'goods_name' => trim($row[14]),
                'goods_english' => trim($row[18]),
            ];
            if(isset($order_id)) {
                $skuArr['order_id'] = $order_id;
            }else{
                $skuArr['order_id'] = $lastId;
            }

//            if (!$order_sn && !$row[0] && $row[12]) {
//                $skuArr['order_id'] = $order_id;
//            }else{
//                $skuArr['order_id'] = $lastId;
//            }

            //$result = $data->order_info()->create($skuArr);
            $result = OrderInfo::create($skuArr);
            if ($result) {
                $success++;
            } else {
                $fail++;
            }


        }

        OrderLog::insert($orderLogArr);    //订单导入日志

        $message = '共' . $sum . '个订单; 订单号已存在：' . $exist . '个;   成功导入:' . $success . '个商品;失败：' . $fail . '个商品';
        session()->flash('excel', $message);
    }


    function valid_date($date)
    {
        //匹配日期格式
        if (date('Y-m-d H:i:s', strtotime($date)) == $date) {
            return true;
        } else {
            return false;
        }
    }


}
