<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderInfo;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as FormatDate;

class ExImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        $err = 0;
        $success = 0;
        $fail = 0;
        $exist = 0;
        $sum = 0;

        $order = new Order();

        foreach ($collection as $key => $row) {
            if ($key == 0) {
                continue;
            }

            $order_sn = trim($row[1]);   //订单sn
            $yun_sn = trim($row[0]);   //运单sn

            $order_data = $order->order_sn($order_sn);dd($order_data);
            if (is_null($order_data)===true){
                $err++;
                $sum++;
                continue;
            }
            if ($order_data && $order_data->yunlu_sn) {
                $exist++;
                $sum++;
                continue;
            }

            $excel_ex = [
                'yunlu_sn' => $yun_sn,  //运单SN
                'ex_status' => 0
            ];

            $result = $order->where('order_sn','=',$order_sn)->update($excel_ex);
            $sum++;
            if ($result) {
                $success++;
            } else {
                $fail++;
            }


        }

        $message = '共' . $sum . '个运单; 运单号已存在：' . $exist . '个;   成功导入:' . $success . '个;失败：' . $fail . '个;错误单号：' . $err . '个';
        session()->flash('excel', $message);


    }
}
