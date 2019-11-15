<?php

namespace App\Imports;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
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
        $order = new Order();


        foreach ($collection as $key=>$row)
        {
            if($key == 0){
                continue;
            }

            $order_sn = trim($row[2]);   //订单sn
            if(!$order_sn){
                $fail++;
                continue;
            }
            $order_data = $order->order_sn($order_sn);
            if($order_data){
                $exist++;
                continue;
            }

            $data = Order::create([

                'ordered_at' => Carbon::parse(FormatDate::excelToDateTimeObject($row[0])),  //下单时间
                'checked_at' => Carbon::parse(FormatDate::excelToDateTimeObject($row[1])),  //审核时间
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

            ]);

            if($data && $row[12]){

                $skuArr = [
                    'goods_sku' => trim($row[12]),
                    'goods_num' => intval(trim($row[13])),
                    'goods_name' => trim($row[14]),
                    'goods_english' => trim($row[18]),
                ];

                $result = $data->order_info()->create($skuArr);

                if($result){
                    $success++;
                }else{
                    $fail++;
                }
            }


        }

        echo '共'. (count($row) -1).'个订单; 成功导入:'.$success .'个; 订单号已存在：'.$exist. '个; 失败：'.$fail. '个';

    }





}
