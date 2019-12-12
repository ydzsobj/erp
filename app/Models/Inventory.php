<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    //绑定数据库

    protected $table = "inventory";

    protected $page_size = 50;

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // protected $guarded = ['LAY_TABLE_INDEX','tempId'];

    /**
     * 深圳真实仓
     */
    const SZ_WAREHOUSE_ID = 1;

    /**
     * 印尼真实仓
     */
    const YN_WAREHOUSE_ID = 3;
    /**
     * 印尼虚拟仓
     */
    const YN_VIRTUAL_WAREHOUSE_ID = 4;

    protected $fillable = [
        'goods_sku',
        'warehouse_id',
        'goods_position',
        'stock_num',
        'in_num',
        'afloat_num'
    ];

    //仓库
    public function warehouse(){
        return $this->hasOne('App\Models\Warehouse','id','warehouse_id');
    }

    //商品
    public function product_goods(){
        return $this->hasOne('App\Models\ProductGoods','id','goods_id');
    }

    public function sku(){
        return $this->belongsTo(ProductGoods::class, 'goods_sku', 'sku_code');
    }

    //搜索
    public function search($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::where('goods_sku','!=','')
            ->keywords($keywords)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $data = static::with(['warehouse','product_goods'])->where('goods_sku','!=','')
            ->keywords($keywords)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$data,$count];
    }

    public function get_data($request){

        $warehouse_id = $request->get('warehouse_id');
        $keywords = $request->get('keywords');

        return self::with(['warehouse','sku'])
            ->leftJoin('product_goods', 'product_goods.sku_code', 'inventory.goods_sku')

            ->where('inventory.warehouse_id', $warehouse_id)
            ->when($keywords, function($query) use ($keywords){
                $query->where(function($sub_query) use ($keywords){
                    $sub_query->where('product_goods.sku_name', 'like', '%'. $keywords. '%')
                        ->orWhere('product_goods.sku_code', $keywords);
                });
            })

            ->select('inventory.*')
            ->orderBy('inventory.id','desc')
            ->paginate($this->page_size);
    }

    public static function by_goods_sku($warehouse_id,$goods_sku){
       return self::where([
           'goods_sku' => $goods_sku,
           'warehouse_id' => $warehouse_id
        ])->first();
    }

    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;
        return $query->where('order_sn','like',"%{$keywords}%")->orWhere('yunlu_sn','like',"%{$keywords}%")
            ->orWhere('order_name','like',"%{$keywords}%")->orWhere('order_address','like',"%{$keywords}%")
            ->orWhere('order_phone','like',"%{$keywords}%")->orWhere('id','like',"%{$keywords}%");
    }

    //仓库状态搜索
    public function scopeWarehouse($query, $warehouse_id){
        if(!$warehouse_id) return $query;
        return $query->where('warehouse_id',$warehouse_id);
    }

    //时间搜索
    public function scopeDate($query, $start_date,$end_date){
        if(!$start_date || !$end_date) return $query;
        $query->whereBetween('created_at', [$start_date, $end_date]);
    }



}
