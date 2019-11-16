<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {
/*
        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :100;

        if($keywords){
            $count = Order::where('order_status','0')->where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->count();
            $data = Order::where('order_status','0')->where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = Order::where('order_status','0')->count();
            $data = Order::where('order_status','0')->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }
*/
        $order = new Order();
        $orders = $order->search($request);
        $count = '1';
        dump($orders);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！']);
    }
}
