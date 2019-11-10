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
                'order_phone' => $row[5],  //收件人电话
                'order_province' => $row[6],  //收件人省
                'order_city' => $row[7],  //收件人市
                //'order_county' => $row[3],  //收件人县
                'order_area' => $row[8],  //收件地区
                'order_address' => $row[9],  //收件人详细地址
                'order_money' => floatval($row[10]),  //代收货款
                'order_currency' => $row[11],  //币种
                'order_country' => $row[21],  //目的国家
                'order_type' => '普通订单',  //订单类型
                'coustomer_text' => $row[22],  //客服备注
                'order_from' => $row[11],  //订单来源

            ]);

            if($data && $row[12]){
                $skus = explode("\n",rtrim($row[12], "\n"));
                $sku_nums = explode("\n", rtrim($row[13], "\n"));
                $sku_names = explode("\n", rtrim($row[14], "\n"));
                $sku_englishes = explode("\n", rtrim($row[18], "\n"));


//                $sku_nums = collect($sku_remarks)->map(function($item){
//                    return collect(explode('x', rtrim($item, "\n")))->last();
//                });


                $order_sku_data = collect($skus)->map(function($item, $key) use($sku_nums,$sku_names,$sku_englishes){
                    return [
                        'goods_sku' => trim($item),
                        'goods_num' => intval($sku_nums[$key]),
                        'goods_name' => trim($sku_names[$key]),
                        'goods_english' => trim($sku_englishes[$key]),
                    ];
                });

                $result = $data->order_info()->createMany($order_sku_data->all());

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
