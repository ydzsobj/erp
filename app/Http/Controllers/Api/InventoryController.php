<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\InventoryImport;
use App\Models\Inventory;
use App\Models\InventoryInfo;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    //获取数据列表
    public function index(Request $request)
    {

        $obj = new Inventory();
        $data = $obj->get_data($request);
        $count = $data->total();

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    public function show(Request $request,$id){
        $model = new Inventory();
        list($data,$count) = $model->search($request,$id);

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //库存总账
    public function all(Request $request){
        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :100;
        //getSql();
        if($keywords){
            //$count = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->count();
            //$data = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->offset(($page-1)*$limit)->limit($limit)->get();
            $count = Inventory::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->count();
            $data = Inventory::with('warehouse','product_goods')->where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('goods_sku','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = Inventory::count();
            $data = Inventory::with('warehouse','product_goods')->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个信息
    public function goods(Request $request,$goods_id)
    {
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        $data = InventoryInfo::where(function($query) use($goods_id,$request) {
            $query->where(['goods_id'=>$goods_id,'warehouse_id'=>$request->warehouse_id]);
        })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

    /**
     * 库存详情
     */
    public function api_info(Request $request){

        $info_obj = new InventoryInfo();

        $data = $info_obj->get_data($request);

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data, 'count' => $data->total()]);
    }

    //待入库列表
    public function waiting_in(Request $request){

        $obj = new InventoryInfo();
        $data = $obj->waiting_in($request);

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data, 'count' => $data->total()]);

    }

    /**
     * 订单出库
     */
    public function order_out(Request $request){

        $warehouse_id = $request->get('warehouse_id');

        //获取待出库的订单列表
        $data = $this->waiting_out_order($warehouse_id, $request);

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data, 'count' => $data->total()]);

    }

    //待出库订单
    public function waiting_out_order($warehouse_id, $request){

        $keywords = $request->get('keywords');

        $data = Order::leftJoin('order_info','order.id','order_info.order_id')
            ->leftJoin('product_goods', 'product_goods.sku_code', 'order_info.goods_sku')
            ->leftJoin('inventory',function($query) use($warehouse_id){
                $query->on('inventory.goods_sku','product_goods.sku_code')
                    ->where('inventory.warehouse_id', $warehouse_id );
            })
            ->where('order.order_lock', 1)
            ->where('order.order_status', '<>',6)
            ->where('order.warehouse_id', $warehouse_id)
            ->when($keywords, function($query) use($keywords){
                $query->where(function($sub_query) use($keywords){
                    $sub_query->where('product_goods.sku_name', 'like', '%'.$keywords. '%')
                        ->orWhere('order_info.goods_sku', $keywords)
                        ->orWhere('order.order_sn', $keywords);
                });
            })
            ->select(
                'order.*',
                'order_info.goods_sku',
                'order_info.goods_num',
                'product_goods.sku_name','product_goods.sku_attr_value_names',
                'inventory.stock_num'
            )
            ->paginate(50);

        return $data;
    }



}
