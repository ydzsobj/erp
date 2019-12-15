<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Order extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "order";
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $page_size = 50;

    const STATUS_EXCEPTION = 10;

    //入库订单搜索
    public function search($request){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:1;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::where('order_used',0)
            ->keywords($keywords)
            ->status($status)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::where('order_used',0)
            ->keywords($keywords)
            ->status($status)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

    //入库订单搜索
    public function searchAll($request){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::with('warehouse')->where('order_sn','!=','')
            ->keywords($keywords)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::with('warehouse')->where('order_sn','!=','')
            ->keywords($keywords)
            ->status($status)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        return [$orders,$count];
    }

    //入库订单搜索
    public function searchImport($request){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:0;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::where('order_status',$status)
            ->keywords($keywords)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::where('order_status',$status)
            ->keywords($keywords)
            //->status($status)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

    //运单订单搜索
    public function searchEx($request){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:0;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $orders = static::where('yunlu_sn','!=','')
            ->keywords($keywords)
            ->exStatus($status)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        return $orders;
    }

    //拣货单搜索
    public function searchPick($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:4;
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

    //问题订单搜索
    public function searchProblem($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:10;
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

    //出库单搜索
    public function searchOut($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:5;
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::where('order_lock','1')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','asc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();



        return [$orders,$count];
    }



    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;
        return $query->where('order_sn','like',"%{$keywords}%")->orWhere('yunlu_sn','like',"%{$keywords}%")
            ->orWhere('order_name','like',"%{$keywords}%")->orWhere('order_address','like',"%{$keywords}%")
            ->orWhere('order_phone','like',"%{$keywords}%")->orWhere('id','like',"%{$keywords}%");
    }

    //订单状态搜索
    public function scopeStatus($query, $status){
        if(!$status) return $query;
        return $query->where('order_status',$status);
    }

    //出库单状态搜索
    public function scopeExStatus($query, $status){
        if(!$status) return $query->where('ex_status',$status);
        return $query->where('ex_status',$status);
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

    //获取订单编号
    public function order_sn($order_sn){
        return self::where('order_sn', $order_sn)->first();
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class,'warehouse_id','id');
    }

    //一对多关联订单信息
    public function order_info(){
        return $this->hasMany(OrderInfo::class,'order_id','id');
    }

    //远程一对多关联库存
    public function inventory(){
        return $this->hasManyThrough('App\Models\Inventory','App\Models\OrderInfo','order_id','goods_sku','id','goods_sku');
    }


     //待出库订单
     public function waiting_out_order($warehouse_id, $request){

        $base_query = $this->base_query($request, $warehouse_id);
        $data = $base_query ->select(
            'order.*',
            'order_info.goods_sku',
            'order_info.goods_num',
            'product_goods.sku_name','product_goods.sku_attr_value_names',
            'inventory.stock_num'
        )->paginate($this->page_size);

        return $data;
    }

    //订单出库基础查询
    public function base_query($request, $warehouse_id){

        $keywords = $request->get('keywords');

        return Order::leftJoin('order_info','order.id','order_info.order_id')
        ->leftJoin('product_goods', 'product_goods.sku_code', 'order_info.goods_sku')
        ->leftJoin('inventory',function($query) use($warehouse_id){
            $query->on('inventory.goods_sku','product_goods.sku_code')
                ->where('inventory.warehouse_id', $warehouse_id );
        })
        ->where('order.order_lock', 1)
        ->where('order.order_status', 4)
        ->where('order.warehouse_id', $warehouse_id)
        ->when($keywords, function($query) use($keywords){
            $query->where(function($sub_query) use($keywords){
                $sub_query->where('product_goods.sku_name', 'like', '%'.$keywords. '%')
                    ->orWhere('order_info.goods_sku', $keywords)
                    ->orWhere('order.order_sn', $keywords);
            });
        });
    }

    //待出库订单导出
    public function export($request, $warehouse_id){

        $base_query = $this->base_query($request, $warehouse_id);
        $data = $base_query->select(
            // 'order.id',
            'order.ordered_at',
            'order.order_checked_at',
            'order.order_sn',
            'order.order_name',
            'order.order_code',
            'order.order_phone',

            'order.order_province',
            'order.order_city',
            'order.order_area',
            'order.order_address',
            'order.order_money',
            'order.order_currency',

            'order_info.goods_sku',
            'order_info.goods_num',
            'order_info.goods_name',
            'order_info.goods_english',

            'product_goods.sku_attr_value_names'
            // 'order_info.goods_attr_names',
            // 'order_info.goods_attr_values'

        )
            ->get();

        return $this->format_out_data($data);

    }

    public function format_out_data($data){

        foreach($data as $d){
            $d->order_phone = ' '. $d->order_phone;
        }

        return $data;

    }

    //标记订单异常
    public function set_status($order_ids = [], $status){

        return Order::whereIn('id', $order_ids)->update([
            'order_status' => $status
        ]);


    }



}
