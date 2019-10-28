<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductGoods;
use App\Models\ShopifyAccount;
use App\Models\ShopifyApi;
use App\Models\ShopifyOrder;
use Illuminate\Support\Arr;

class ShopifyAccountController extends Controller
{
    public function index(Request $request){

        $shop_ac = new ShopifyAccount();
        $data = $shop_ac->get_data($request);

        $count = $data->total();

        $data = $this->format_data($data);

        return response()->json([
            'code' => 0,
            'count' => $count,
            'msg' => '获取数据成功',
            'data' => $data
        ]);
    }

    protected function format_data($data){

        $countries = config('order.country_list');

        foreach($data as $d){
            $country = Arr::get($countries, $d->country_id);
            $d->country_name = $country['name'];
        }

        return $data;
    }
}
