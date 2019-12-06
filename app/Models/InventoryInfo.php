<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryInfo extends Model
{
    //绑定数据库

    protected $table = "inventory_info";

    public $page_size = 10;

    const STOCK_TYPE_NAME_OUT = '虚拟仓出库';
    const STOCK_TYPE_NAME_IN = '虚拟仓入库';

    protected $fillable = [
        'goods_sku',
        'warehouse_id',
        'stock_num',
        'in_num',
        'user_id',
        'stock_type'
    ];

    public function admin(){
        return $this->belongsTo(Admin::class, 'user_id', 'id');
    }

    public function sku(){
        return $this->belongsTo(ProductGoods::class, 'goods_sku', 'sku_code');
    }

    function get_data($request){

        $warehouse_id = $request->get('warehouse_id');
        $goods_sku = $request->get('goods_sku');

        return self::with(['admin','sku'])->where([
            'warehouse_id' => $warehouse_id,
            'goods_sku' => $goods_sku
        ])
            ->orderBy('id','desc')
            ->paginate($this->page_size);

    }
}
