<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class InventoryInfo extends Model
{
    //绑定数据库

    protected $table = "inventory_info";

    public $page_size = 50;

    const STOCK_TYPE_NAME_OUT = '虚拟仓出库';
    const STOCK_TYPE_NAME_IN = '虚拟仓到货';

    protected $fillable = [
        'goods_sku',
        'warehouse_id',
        'stock_num',
        'in_num',
        'out_num',
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
        $in_status = $request->get('in_status');

        return self::with(['admin','sku'])
            ->when($warehouse_id, function($query) use ($warehouse_id) {
                $query->where('warehouse_id', $warehouse_id);
            })
            ->when($goods_sku, function($query) use ($goods_sku) {
                $query->where('goods_sku', $goods_sku);
            })
            ->when(!is_null($in_status), function($query) use ($in_status) {

                if($in_status == 0){
                    $query->where('in_status', $in_status)->where('out_num', '>', 0);
                }

            })
            ->orderBy('id','desc')
            ->paginate($this->page_size);

    }
}
