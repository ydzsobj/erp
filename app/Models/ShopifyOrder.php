<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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
    /**
     * 已拒绝
     */
    const STATUS_REFUSED = 7;


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

    public static function audit_status_options(){
        return [
            self::STATUS_AUDITED => '审核通过',
            self::STATUS_REFUSED => '审核拒绝',
        ];
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
                'order_skus.sku.sku_values.attr_value',
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

        $orders =  self::with([
                    'order_skus',
                    'order_skus.sku.sku_values.attr_value',
                ]
            )
            ->ofKeywords($keywords)
            ->ofStatus($status)
            ->ofSubmitOrderDate($start_date, $end_date)
            ->ofCountryId($country_id)
            ->select(
                'id',
                'submit_order_at',
                'last_audited_at',
                'sn',
                'receiver_name',
                'postcode',
                'receiver_phone',
                'country_id',
                'province',
                'city',
                'area',
                'address1',
                'address2',
                'company',
                'price',
                'status',
                'remark'

            )
                ->orderBy('submit_order_at', 'desc')
                ->get();

        $status = config('order.status_list');
        $country_list = config('order.country_list');

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
                $sku = $order_sku->sku;

                if($sku->count() == 0){
                    continue;
                }

                //skuid
                $sku_ids .= $sku->sku_code;
                $sku_ids .= "\r\n";

                //备注-中文
                $sku_str .= $sku->sku_name .' '. $sku->sku_attr_value_names. ' x'. $order_sku->sku_nums;
                $sku_str .= "\r\n";

                //物品描述-英文
                $sku_attr_values = $sku->sku_values;
                if($sku_attr_values->count() >0){
                    $attr_values_str = $sku_attr_values->map(function($item){
                        return $item->attr_value->attr_value_english;
                    });
                    // dump($attr_values_str);
                    $sku_desc_str .= $sku->sku_english. ' '.$attr_values_str->implode(',') .' x'. $order_sku->sku_nums;
                }

                $sku_desc_str .= "\r\n";

                //产品中文名称
                $product_name_str .= $sku->sku_name;
                $product_name_str .= "\r\n";

                //产品英文名称
                $product_english_name_str .= $sku->sku_english;
                $product_english_name_str .= "\r\n";

                //件数
                $total_nums += $order_sku->sku_nums;

            }

            $order->sku_ids = $sku_ids;
            $order->sku_str = $sku_str;
            $order->product_name_str = $product_name_str;
            $order->product_english_name_str = $product_english_name_str;
            $order->total_nums = $total_nums;
            $order->sku_desc_str = $sku_desc_str;

            $order->status_str = Arr::get($status, $order->status, '');
            $country = Arr::get($country_list, $order->country_id, '');
            $order->country_name = $country['name'];
            $order->service_remark = $order->remark;

            unset(
                $order->id,
                $order->country_id,
                $order->status,
                $order->remark,
                $order->order_skus
            );
        }

        // dd($orders->toArray());

        return $orders;

    }

    //抓取订单
    public function create_order($shopify_account){

        $admin_id = Auth::user() ? Auth::user()->id : 0;

        $country_id = $shopify_account->country_id;

        $successed = 0;
        $failed = 0;
        $existed = 0;

        $api = new ShopifyApi($shopify_account);

        list($api_result, $msg) = $api->orders();

        if(!$api_result){
            return [false, $msg];
        }

        $shopify_orders = $api_result['orders'];

        foreach($shopify_orders as $shopify_order){

            $sn = $shopify_order['id'];

            if($sn){
                $order = new ShopifyOrder();
                $existed_order = $order->by_sn($sn);
                if($existed_order){
                    $existed++;
                    continue;
                }
            }

            $submit_order_at = Carbon::parse($shopify_order['created_at'])->toDateTimeString();
            $price = $shopify_order['total_price'];
            $total_off = $shopify_order['total_discounts'];

            if(isset($shopify_order['shipping_address'])){
                $address_info = $shopify_order['shipping_address'];
            }else{
                $address_info = $shopify_order['customer']['default_address'];
            }

            $postcode = $address_info['zip'];
            $receiver_name = $address_info['name'];
            $receiver_phone = trim($address_info['phone']);
            $province = $address_info['province'];
            $city = $address_info['city'];
            $area = '';
            $address1 = $address_info['address1'];
            $address2 = $address_info['address2'];
            $company = $address_info['company'];

            $order = (compact(
                'sn',
                'submit_order_at',
                'price',
                'total_off',
                'postcode',
                'receiver_name',
                'receiver_phone',
                'province',
                'city',
                'area',
                'address1',
                'address2',
                'company',
                'admin_id',
                'country_id'
            ));

            // dd($shopify_order,$order);

            $mod = ShopifyOrder::create($order);

            if($mod){
                $successed++;
                $line_items = $shopify_order['line_items'];
                $order_skus = collect([]);
                $amount = 0;
                foreach($line_items as $item){
                    $amount += $item['quantity'];
                    $order_skus->push([
                        'sku_nums' => $item['quantity'],
                        'sku_id' => $item['sku'],
                        'price' => $item['price'],
                    ]);
                }
                // dd($order_skus);
                $mod->amount = $amount;
                $mod->save();
                $mod->order_skus()->createMany($order_skus->all());
            }else{
                $failed++;
            }
        }

        return [true, '店铺id='.$shopify_account->id.',共获取到'.count($shopify_orders). '个订单; 添加成功:'.$successed.'个；失败：'.$failed.' 个； 订单已存在：'.$existed.'个' ];
    }

}
