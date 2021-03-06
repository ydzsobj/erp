<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductGoods;
use App\Models\ShopifyAccount;
use App\Models\ShopifyApi;
use App\Models\ShopifyOrder;
use Illuminate\Support\Arr;

class ShopifyOrderController extends Controller
{
    public function index(Request $request){

        $o = new ShopifyOrder();
        list($orders, $search) = $o->get_data($request);
        $count = $orders->total();

        $orders = $this->format_data($orders);

        return response()->json([
            'code' => 0,
            'count' => $count,
            'msg' => '获取数据成功',
            'data' => $orders,
        ]);
    }

    public function show(Request $request, $id){
        $detail = ShopifyOrder::find($id);
        $status = config('order.status_list');
        $status_name = Arr::get($status, $detail->status);
        $detail->status_name = $this->status_color($detail->status, $status_name);
        return returned(true,'',$detail);
    }

    protected function format_data($data){

        $status = config('order.status_list');
        $countries = config('order.country_list');

        foreach($data as $d){
            $status_name = Arr::get($status, $d->status);
            $d->status_name = $this->status_color($d->status, $status_name);
            $country = Arr::get($countries, $d->country_id);
            $d->country_name = $country['name'];
            $d->price = floatval($d->price - $d->total_off);
            $d->currency_code = $country['currency_code'];
        }

        return $data;
    }

    public function status_color($status, $status_name){

        $color = '';
        if($status == 1){
            $color = 'red';
        }else if($status == 2){
            $color = 'green';
        }else if($status == 7){
            $color = 'orange';
        }else if($status == 6){
            $color = 'pink';
        }

        return "<span style='color:" . $color ."'>" . $status_name ."</span>";
    }
}
