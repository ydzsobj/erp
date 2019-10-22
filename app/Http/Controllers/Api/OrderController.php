<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request){

        $o = new Order();
        list($orders, $search) = $o->get_data($request);
        $count = $orders->total();

        $orders = $this->format_data($orders);

        return response()->json([
            'code' => 0,
            'count' => $count,
            'msg' => '获取数据成功',
            'data' => $orders,
            'search' => $search
        ]);
    }

    protected function format_data($data){

        $status = config('order.status_list');
        $countries = config('order.country_list');

        foreach($data as $d){
            $d->status_name = array_get($status, $d->status);
            $d->country_name = array_get($countries, $d->country_id);
        }

        return $data;
    }
}
