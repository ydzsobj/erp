<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class InventoryInfo extends Model
{
    //绑定数据库

    protected $table = "inventory_info";

    public $page_size = 50;

    const STOCK_TYPE_NAME_OUT = '虚拟仓出库';
    const STOCK_TYPE_NAME_IN = '虚拟仓到货';
    const STOCK_TYPE_NAME_ORDER_OUT = '订单出库';

    protected $fillable = [
        'goods_sku',
        'warehouse_id',
        'stock_num',
        'in_num',
        'out_num',
        'user_id',
        'stock_type',
        'out_status',
        'targetable_type',
        'targetable_id',
        'import_order_sn'
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
        $out_status = $request->get('out_status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $keywords = $request->get('keywords');
        $targetable_type = $request->get('targetable_type');
        $targetable_id = $request->get('targetable_id');
        $import_order_sn = $request->get('import_order_sn');

        return self::with(['admin','sku'])

            ->leftJoin('product_goods', 'product_goods.sku_code', 'inventory_info.goods_sku')

            ->leftJoin('order',function($query){
                $query->on('inventory_info.targetable_id', 'order.id')
                    ->where('targetable_type', 'order');
            })

            ->when($keywords, function($query) use ($keywords){
                $query->where(function($sub_query) use ($keywords){
                    $sub_query->where('product_goods.sku_name', 'like', '%'. $keywords. '%')
                        ->orWhere('product_goods.sku_code', $keywords);
                });
            })
            ->when($warehouse_id, function($query) use ($warehouse_id) {
                $query->where('inventory_info.warehouse_id', $warehouse_id);
            })
            ->when($goods_sku, function($query) use ($goods_sku) {
                $query->where('inventory_info.goods_sku', $goods_sku);
            })
            ->when(!is_null($out_status), function($query) use ($out_status) {

                if($out_status == 0){
                    $query->where('inventory_info.out_status', $out_status)->where('inventory_info.in_num','>',0);
                }else if($out_status == 1){
                    $query->where('inventory_info.out_status', $out_status)->where('inventory_info.out_num','>',0);
                }else{
                    $query->where('inventory_info.out_status', $out_status);
                }

            })
            ->when($start_date && $end_date, function($query) use($start_date, $end_date){
                $query->whereBetween('inventory_info.created_at', [$start_date, $end_date]);
            })
            ->when($targetable_id && $targetable_type, function($query) use ($targetable_type, $targetable_id){
                $query->where([
                    'targetable_type' => $targetable_type,
                    'targetable_id' => $targetable_id
                ]);
            })
            ->when(!is_null($import_order_sn), function($query) use ($import_order_sn){
                if($import_order_sn){
                    $query->where('import_order_sn', $import_order_sn);
                }else{
                    $query->whereNotNull('import_order_sn');
                }
            })
            ->select(
                'inventory_info.*',
                'order.order_sn'
            )
            ->orderBy('inventory_info.id','desc')
            ->paginate($this->page_size);

    }

    //汇总待入库的数据
    public function waiting_in(){

        $summary = InventoryInfo::where('warehouse_id',Warehouse::YN_VIRTUAL_WAREHOUSE_ID)
                ->where(['out_status' => 1])
                ->where('inventory_info.out_num','>',0)
                ->select(DB::raw('SUM(out_num) as total_out_nums'),'goods_sku')
                ->groupBy('goods_sku')
                ->get();

        $goods_sku_list = $summary->pluck('goods_sku');

        $skus = ProductGoods::whereIn('sku_code', $goods_sku_list->all())
            ->select(
                'product_goods.*'
            )
            ->paginate($this->page_size);

        $summary_data = $summary->keyBy('goods_sku');

        $skus->map(function($item) use ($summary_data){

            $target = Arr::get($summary_data, $item->sku_code);
            $item['out_num'] = $target->total_out_nums;
            $item['waiting_ids'] = $this->waiting_ids($item->sku_code);
            return $item;
        });

        return $skus;

    }

    public function waiting_ids($sku_code){

        return InventoryInfo::where('warehouse_id',Warehouse::YN_VIRTUAL_WAREHOUSE_ID)
                ->where(['out_status' => 1])
                ->where('inventory_info.out_num','>',0)
                ->where('goods_sku', $sku_code)
                ->pluck('id');
    }
}
