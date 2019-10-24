<?php

namespace App\Imports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrdersImport implements ToCollection
{
    private $country_id;

    public function __construct($country_id)
    {
        $this->country_id = $country_id;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
//        dd($rows);
        $successed = 0;
        $failed = 0;
        $existed = 0;

        //admin
        $admin = Auth::user();

        foreach ($rows as $key=>$row){
            if($key == 0){
                continue;
            }

            $sn = trim($row[2]);

            if(!$sn){
                $failed++;
                continue;
            }

            $o = new Order();
            $order = $o->by_sn($sn);
            if($order){
                $existed++;
                continue;
            }

            $mod = Order::create([
                'submit_order_at' => $row[0],
                'sn' => trim($row[2]),
                'price' => floatval($row[10]),
                'amount' => intval($row[13]),
                'country_id' => $this->country_id,
                'postcode' => intval($row[4]),
                'receiver_name'=> $row[3],
                'receiver_phone'=> $row[5],
                'province'=> $row[6],
                'city'=> $row[7],
                'area'=> $row[8],
                'short_address' => $row[9],
                'admin_id' => $admin->admin_id,
            ]);

            if($mod && $row[11]){
                //插入skus
                $skus = explode("\n",rtrim($row[11], "\n"));
                $sku_remarks = explode("\n", rtrim($row[12], "\n"));

                $sku_nums = collect($sku_remarks)->map(function($item){
                    return collect(explode('x', rtrim($item, "\n")))->last();
                });

                $order_sku_data = collect($skus)->map(function($item, $key) use($sku_nums){
                   return ['sku_id' => trim($item), 'sku_nums' => intval($sku_nums->get($key)) ];
                });

                $result = $mod->order_skus()->createMany($order_sku_data->all());

                if($result){
                    $successed++;
                }else{
                    $failed++;
                }
            }
        }

        echo '共'. (count($rows) -1).'个订单; 成功导入:'.$successed .'个; 订单号已存在：'.$existed. '个; 失败：'.$failed. '个';
    }
}
