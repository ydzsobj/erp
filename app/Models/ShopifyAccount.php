<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ShopifyAccount extends Model
{
    protected $table = 'shopify_accounts';

    use SoftDeletes;

    protected $fillable = [
        'api_key',
        'api_secret',
        'api_domain',
        'name',
        'country_id'
    ];

    public function get_data($request){

        $keywords = $request->get('keywords');

        return self::ofKeywords($keywords)->paginate(10);
    }

    public function scopeOfKeywords($query, $keywords){
        if($keywords){
            $query->where(function($sub_query) use ($keywords){
                $sub_query->where('name', 'like', '%'. $keywords. '%')
                    ->orWhere('id', $keywords);
            });
        }else{
            return $query;
        }
    }

    //抓取订单
    public function create_order($shopify_account){

        $admin_id = Auth::user()->id;

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

            $shipping_address = $shopify_order['shipping_address'];
            $postcode = $shipping_address['zip'];
            $receiver_name = $shipping_address['name'];
            $receiver_phone = trim($shipping_address['phone']);
            $province = $shipping_address['province'];
            $city = $shipping_address['city'];
            $area = '';
            $address1 = $shipping_address['address1'];
            $address2 = $shipping_address['address2'];
            $company = $shipping_address['company'];

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

        return [true, '共获取到'.count($shopify_orders). '个订单; 添加成功:'.$successed.'个；失败：'.$failed.' 个； 订单已存在：'.$existed.'个' ];
    }

}
