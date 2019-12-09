<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderInfo;
use App\Models\ProductGoods;
use Illuminate\Http\Request;

class PurchasePoolController extends Controller
{
    //获取列表数据
    public function index(Request $request)
    {
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :100;

        $order = Order::with(['order_info'=>function($query){
            $query->where('goods_status','0');
        }])->where('order_status',2)->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get()->toArray();
        $data = [];
        foreach ($order as $key=>$value){
            foreach ($value['order_info'] as $k=>$v){
                $sku = ProductGoods::where('sku_code',$v['goods_sku'])->first();
                if(array_key_exists($v['goods_sku'],$data)){
                    $data[$v['goods_sku']]['order_num'] += $v['goods_num'];
                    $data[$v['goods_sku']]['ids'] = $data[$v['goods_sku']]['ids'].','.$v['id'];
                }else{
                    $data[$v['goods_sku']]['id'] = $sku['id'];
                    $data[$v['goods_sku']]['ids'] = $v['id'];
                    $data[$v['goods_sku']]['goods_sku'] = $v['goods_sku'];
                    $data[$v['goods_sku']]['order_num'] = $v['goods_num'];
                    $data[$v['goods_sku']]['goods_name'] = $sku['sku_name'];
                    $data[$v['goods_sku']]['goods_english'] = $sku['sku_english'];
                    $data[$v['goods_sku']]['goods_attr_name'] = $sku['sku_attr_names'];
                    $data[$v['goods_sku']]['goods_attr_value'] = $sku['sku_attr_value_names'];
                    $data[$v['goods_sku']]['goods_price'] = $sku['sku_price'];
                    $data[$v['goods_sku']]['inventory'] = Inventory::with('warehouse')->where('goods_sku',$v['goods_sku'])->get();
                }

            }
        }

        $count = count($data);
        $skuKey =  array_column( $data, 'goods_sku');
        array_multisort($skuKey, SORT_ASC, $data);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    public function show($id)
    {
        //显示
        $data = Inventory::with('warehouse')->where('goods_sku',$id)->get();
        $count = count($data);
        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }





}
