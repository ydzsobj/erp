<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PurchasePoolController extends Controller
{
    //获取列表数据
    public function index(Request $request)
    {
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :100;

        $order = Order::with('order_info')->where('order_status',1)->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get()->toArray();
        $data = [];
        foreach ($order as $key=>$value){
            foreach ($value['order_info'] as $k=>$v){
                if(array_key_exists($v['goods_sku'],$data)){
                    $data[$v['goods_sku']]['goods_num'] += $v['goods_num'];
                }else{
                    $data[$v['goods_sku']]['goods_sku'] = $v['goods_sku'];
                    $data[$v['goods_sku']]['goods_name'] = $v['goods_name'];
                    $data[$v['goods_sku']]['goods_num'] = $v['goods_num'];
                }
            }
        }
        $count = count($data);
        $skuKey =  array_column( $data, 'goods_sku');
        array_multisort($skuKey, SORT_ASC, $data);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }
}
