<?php

namespace App\Imports;

use App\Models\InventoryImportLog;
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

        $msg = '';

        //admin
        $admin = Auth::user();

        //增加导入日志
        $log_mod = InventoryImportLog::create([
            'import_nums' => count($rows) - 1,
            'admin_id' => $admin->id,
            'warehouse_id' => $this->warehouse_id
        ]);

        foreach ($rows as $key=>$row){
            if($key == 0){
                continue;
            }

            $sku_code = trim($row[1]);

            if(!$sku_code){
                $failed++;
                continue;
            }

            $import_order_sn = $row[3];

            $order_sn_existed = InventoryInfo::where('import_order_sn', $import_order_sn)->first();

            if($order_sn_existed){
                $existed++;
                $msg .= '订单号:'.$import_order_sn. '已存在；';
                continue;
            }

            $existed_data = Inventory::by_goods_sku($this->warehouse_id, $sku_code);

            if($existed_data){
                //sku已存在，追加库存信息
                $existed_data->stock_num += intval($row[2]);
                $existed_data->in_num += intval($row[2]);
                // $existed_data->afloat_num += ($row[3]);
                // $existed_data->goods_position = ($row[3]);
                // $existed_data->goods_text = ($row[4]);
                $mod = $existed_data->save();

            }else{
                //sku新入库 添加数据
                $mod = Inventory::create([
                    'goods_sku' => $row[1],
                    'warehouse_id' => $this->warehouse_id,
                    'stock_num' => intval($row[2]),
                    'Inventory::create' => intval($row[2]),
                    'in_num' => intval($row[2]),
                    // 'afloat_num' => intval($row[3]),
                    // 'goods_position' => $row[3],
                    // 'goods_text' => $row[4]
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
                    'user_id' => $admin->id,
                    'import_order_sn' => $row[3],
                    'targetable_type' => InventoryImportLog::TABLE,
                    'targetable_id' => $log_mod->id
                ]);
            }else{
                $failed++;
            }
        }

        if($successed == 0){
            //删除日志
            $log_mod->delete();
        }else{
            $log_mod->import_nums = $successed;
            $log_mod->save();
        }

        echo '共'. (count($rows) -1).'条数据; 成功导入:'.$successed .'个; 失败：'.$failed. '个, 订单号已存在：'. $existed. '个;';
        echo "\n";
        echo $msg;
    }
}
