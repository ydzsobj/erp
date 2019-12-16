<?php

namespace App\Imports;

use App\Models\InventoryCheckInfo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class InventoryCheckImport implements ToCollection
{
    private $warehouse_id;
    private $inventory_check_code;

    public function __construct($warehouse_id,$inventory_check_code)
    {
        $this->warehouse_id = $warehouse_id;
        $this->inventory_check_code = $inventory_check_code;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //
        $check_data = [
            'warehouse_id'=>$this->warehouse_id,
            'inventory_check_code'=>$this->inventory_check_code,
            'user_id' =>  Auth::guard('admin')->user()->id,
            'inventory_check_status' => 0,
            'created_at' => Carbon::now()
        ];

        $lastId = DB::table('inventory_check')->insertGetId($check_data);

        foreach ($collection as $key => $row) {
            if ($key < 2) {
                continue;
            }


            $excel_data[$key] = [
                'goods_sku'=>trim($row[0]),
                'goods_name'=>trim($row[1]),
                'goods_color'=>trim($row[2]),
                'goods_size'=>trim($row[3]),
                'goods_num'=>intval($row[4]),
                'inventory_check_id'=>$lastId,
                'created_at' => Carbon::now()
            ];


        }

        InventoryCheckInfo::insert($excel_data);

        $message = '盘点单导入成功！';
        session()->flash('excel', $message);


    }



}
