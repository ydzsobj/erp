<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductGoods;
use App\Models\ProductToAttr;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //获取产品列表
    public function index(Request $request)
    {

        $keywords = $request->get('keywords');
        $page = $request->page ? $request->page : 1;
        $limit = $request->limit ? $request->limit :50;
        //getSql();
        if($keywords){
            //$count = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->count();
            //$data = Product::where('id',$keywords)->orWhere('product_name','like',"%{$keywords}%")->offset(($page-1)*$limit)->limit($limit)->get();
            $count = Product::where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('product_name','like',"%{$keywords}%");
            })->count();
            $data = Product::with('category')->where(function ($query) use ($keywords){
                $query->where('id','like',"%{$keywords}%")
                    ->orWhere('product_name','like',"%{$keywords}%");
            })->orderBy('id','desc')->offset(($page-1)*$limit)->limit($limit)->get();

        }else{
            $count = Product::count();
            $data = Product::with('category')->orderByDesc('id')->offset(($page-1)*$limit)->limit($limit)->get();
        }

        return response()->json(['code'=>0,'count'=>$count,'msg'=>'成功获取数据！','data'=>$data]);
    }


    //获取单个产品信息
    public function show($id)
    {

        $data = Product::with([
            'productAttr.attr_values' => function($query) use ($id){
                $query->where('product_id', $id);
            },

            'skus.sku_values'
        ])->where('id', $id)
        ->first();

        foreach($data->productAttr as $obj){
            $obj->attr = Attribute::find($obj->attr_id);

            $attr_values = $obj->attr_values->map(function($item){
                $attr_value = AttributeValue::find($item->attr_value_id);
                $item->attr_value_english = $attr_value->attr_value_english;
                return $item;
            });

            $obj->attr_values = $attr_values;
        }

        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个产品信息
    public function get_sku($id)
    {
        $data = ProductGoods::where('product_id',$id)->get();
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }

    //获取单个产品信息
    public function sku($id)
    {
        $product_goods = ProductGoods::where('product_id',$id)->get();
        foreach ($product_goods as $key=>$value){
            $data[$key]['sku_id'] = $value['id'];
            $data[$key]['price'] = $value['sku_price'];
            $data[$key]['stock'] = $value['sku_num'];
            $data[$key]['sku_image_url'] = $value['sku_image'];
            $ids = explode(',',$value['sku_attr_value_ids']);
            $names = explode(';',$value['sku_attr_value_names']);
            foreach ($ids as $k=>$v){
                $data[$key]['attrs'][$k]['sku_value_id'] = $ids[$k];
                $data[$key]['attrs'][$k]['sku_value_names'] = $names[$k];
            }
        }
        return response()->json(['code'=>0,'msg'=>'成功获取数据！','data'=>$data]);
    }





}
