<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $page_size = 20;

    protected $fillable = [

        'sn',
        'price',
        'amount',
        'total_off',
        'status',
        'country_id',
        'postcode',
        'receiver_name',
        'receiver_phone',
        'province',
        'city',
        'area',
        'short_address',
        'submit_order_at',
        'admin_id',

        'last_audited_at',
        'audited_admin_id',
    ];

    /**
     * 未审核
     */
    const STATUS_NO_AUDIT = 1;
    /**
     * 已审核
     */
    const STATUS_AUDITED = 2;
    /**
     * 已取消
     */
    const STATUS_CANCELLED = 6;


    public function order_skus(){
        return $this->hasMany(OrderSku::class);
    }

    public function admin_user(){
        return $this->belongsTo(Admin::class,'admin_id','id');
    }

    public function audited_admin_user(){
        return $this->belongsTo(Admin::class, 'audited_admin_id', 'id')->withDefault();
    }

    public function audit_logs(){
        return $this->hasMany(OrderAuditLog::class);
    }

    public function by_sn($sn){
        return self::where('sn', $sn)->first();
    }

    public function get_data($request){

        $limit = $request->get('limit');
        $keywords = $request->get('keywords');
        $status = $request->get('status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $per_page = $limit ?: $this->page_size;

        $orders = self::with([
                'admin_user:id,admin_name',
                'audited_admin_user:id,admin_name',
                'order_skus',
                'order_skus.sku:sku_code,sku_name',
                'order_skus.sku.sku_values',
                'audit_logs' => function($query){
                    $query->orderBy('id', 'desc');
                },
                'audit_logs.admin_user:id,admin_name'
            ])
            ->ofKeywords($keywords)
            ->ofStatus($status)
            ->ofSubmitOrderDate($start_date, $end_date)
            ->select('orders.*')
            ->orderBy('orders.submit_order_at','desc')
            ->paginate($per_page);

        $search = compact('per_page');

        return [$orders, $search ];
    }

    public function scopeOfKeywords($query, $keywords){

        if(!$keywords){
            return $query;
        }

        $sku_ids = ProductGoods::where('sku_name', $keywords)->orWhere('sku_code', $keywords)->pluck('sku_code');
        if($sku_ids->count() > 0)
        {
            $order_ids = OrderSku::whereIn('sku_id', $sku_ids)->pluck('order_id')->unique();
            if($order_ids->count() >0){
                return $query->whereIn('id', $order_ids);
            }
        }else{
            return $query->where('sn', $keywords);
        }
    }

    public function scopeOfStatus($query, $status){
        if(!$status){
            return $query;
        }
        return $query->where('status', $status);
    }

    public function scopeOfSubmitOrderDate($query, $start_date,$end_date){
        if(!$start_date || !$end_date){
            return $query;
        }

        $query->whereBetween('submit_order_at', [$start_date, $end_date]);
    }
}
