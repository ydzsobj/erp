<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\InventoryInfo;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class InventoryImport implements ToCollection
{
    private $warehouse_id;

    public function __construct($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
    //    dd($rows);
        $successed = 0;
        $failed = 0;
        $existed = 0;

        //admin
        $admin = Auth::user();

        foreach ($rows as $key=>$row){
            if($key == 0){
                continue;
            }

            $sku_code = trim($row[1]);

            if(!$sku_code){
                $failed++;
                continue;
            }

            $existed_data = Inventory::by_goods_sku($this->warehouse_id, $sku_code);

            if($existed_data){
                //sku已存在，追加库存信息
                $existed_data->stock_num += intval($row[2]);
                $existed_data->in_num += intval($row[2]);
                // $existed_data->afloat_num += ($row[3]);
                $existed_data->goods_position = ($row[3]);
                $existed_data->goods_text = ($row[4]);
                $mod = $existed_data->save();

            }else{
                //sku新入库 添加数据
                $mod = Inventory::create([
                    'goods_sku' => $row[1],
                    'warehouse_id' => $this->warehouse_id,
                    'stock_num' => intval($row[2]),
                    'in_num' => intval($row[2]),
                    // 'afloat_num' => intval($row[3]),
                    'goods_position' => $row[3],
                    'goods_text' => $row[4]
                ]);
            }

            //详情表增加一条数据
            if($mod){
                $successed++;
                InventoryInfo::create([
                    'goods_sku' => $row[1],
                    'warehouse_id' => $this->warehouse_id,
                    // 'stock_num' => intval($row[2]),
                    'in_num' => intval($row[2]),
                    'stock_type' => '库存导入',
                    'user_id' => $admin->id
                ]);
            }else{
                $failed++;
            }
        }

        echo '共'. (count($rows) -1).'条数据; 成功导入:'.$successed .'个; 失败：'.$failed. '个';
    }
}