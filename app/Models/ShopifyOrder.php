<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    protected $table = 'shopify_orders';

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
        'address1',
        'address2',
        'company',
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
        return $this->hasMany(ShopifyOrderSku::class);
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
        $country_id = $request->get('country_id');

        $per_page = $limit ?: $this->page_size;

        $orders = self::with([
                'admin_user:id,admin_name',
                'audited_admin_user:id,admin_name',
                'order_skus',
                'order_skus.sku.sku_values',
                'audit_logs' => function($query){
                    $query->orderBy('id', 'desc');
                },
                'audit_logs.admin_user:id,admin_name'
            ])
            ->ofKeywords($keywords)
            ->ofStatus($status)
            ->ofSubmitOrderDate($start_date, $end_date)
            ->ofCountryId($country_id)
            ->select('shopify_orders.*')
            ->orderBy('shopify_orders.submit_order_at','desc')
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
            $order_ids = ShopifyOrderSku::whereIn('sku_id', $sku_ids)->pluck('shopify_order_id')->unique();
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

    public function scopeOfCountryId($query, $country_id){

        if($country_id){
            return $query->where('country_id', $country_id);
        }else{
            return $query;
        }
    }

    //详情
    public function detail($id){
        $detail = self::with(['order_skus','order_skus.sku.sku_values'])->where('id', $id)->first();
        return $detail;
    }

    public function export($request){

        $limit = $request->get('limit');
        $keywords = $request->get('keywords');
        $status = $request->get('status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $country_id = $request->get('country_id');

        $orders =  self::with(['order_skus','order_skus.sku.sku_values'])
            ->ofKeywords($keywords)
            ->ofStatus($status)
            ->ofSubmitOrderDate($start_date, $end_date)
            ->ofCountryId($country_id)
            ->select(
                'good_orders.id',
                'good_orders.created_at',
                'good_orders.last_audited_at',
                'good_orders.sn',
                'good_orders.receiver_name',
                'good_orders.postcode',
                'good_orders.receiver_phone',
                'good_orders.province',
                'good_orders.city',
                'good_orders.area',
                'good_orders.short_address',
                'good_orders.price',

                'good_orders.status',
                'good_orders.pay_type_id',
                'good_orders.remark'

            )
                ->orderBy('good_orders.id', 'desc')
                ->get();

        $status = config('order.status_list');

        foreach ($orders as $order){
            $order->sn = ' '.$order->sn;
            $order->receiver_phone = ' '.$order->receiver_phone;
            $order_skus = $order->order_skus;
            $sku_ids = '';
            $sku_str = '';
            $sku_desc_str = '';
            $product_name_str = '';
            $product_english_name_str = '';
            $total_nums = 0;
            $admin_user_str = '';

            foreach ($order_skus as $order_sku){
                $sku = $order_sku->sku_info;

                //skuid
                $sku_ids .= $sku->sku_id;
                $sku_ids .= "\r\n";

                //备注-中文
                $sku_str .= $sku->good->name .' '. $sku->s1_name . $sku->s2_name. $sku->s3_name. ' x'. $order_sku->sku_nums;
                $sku_str .= "\r\n";

                //物品描述-英文
                $sku_desc_str .= $sku->good->product->english_name .' '. ProductAttributeValue::get_english_name($sku->good_id, [$sku->s1,$sku->s2,$sku->s3]). ' x'. $order_sku->sku_nums;
                $sku_desc_str .= "\r\n";

                //产品中文名称
                $product_name_str .= $sku->good->product->name;
                $product_name_str .= "\r\n";

                //产品英文名称
                $product_english_name_str .= $sku->good->product->english_name;
                $product_english_name_str .= "\r\n";

                //件数
                $total_nums += $order_sku->sku_nums;

                //所属人
                $admin_user_str .= $sku->good->admin_user->name;
                $admin_user_str .= "\r\n";


            }

            $order->sku_ids = $sku_ids;
            $order->sku_str = $sku_str;
            $order->product_name_str = $product_name_str;
            $order->product_english_name_str = $product_english_name_str;
            $order->total_nums = $total_nums;
            $order->sku_desc_str = $sku_desc_str;
            $order->admin_user_str = $admin_user_str;

            $order->status_str = array_get($status, $order->status, '');
            $order->pay_type_str = array_get($pay_types, $order->pay_type_id, '');
            $order->service_remark = $order->remark;


            unset(
                $order->id,
                $order->pay_type_id,
                $order->status,
                $order->remark
            );
        }

//        dd($orders->toArray());

        return $orders;

    }

}
