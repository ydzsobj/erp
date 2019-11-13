<?php
namespace App\Http\Controllers\Erp;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttr;
use App\Models\ProductGoods;
use App\Models\ProductToAttr;
use App\Models\SkuAttrValue;
use App\Models\Supplier;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        $category = (new Category())->group();
        $brand = Brand::where('brand_status','1')->get();
        $supplier = Supplier::where('supplier_status','1')->get();
        return view('erp.product.create', compact('category','brand','supplier'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $spuId = $this->createSpuCode($request->category_id);
        //存储表单信息
        $arr = [
            'product_name' => $request->product_name,
            'product_english' => $request->product_english,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'product_code' => $spuId,
            'product_barcode' => $request->product_barcode,
            'product_cost_price' => $request->product_cost_price,
            'product_price' => $request->product_price,
            'product_freight' => $request->product_freight,
            'product_size' => $request->product_size,
            'product_weight' => $request->product_weight,
            'product_image' => $request->product_image,
            'product_content' => $request->product_content,
            'supplier_id' => $request->supplier_id,
            'supplier_bid' => $request->supplier_bid,
            'supplier_url' => $request->supplier_url,
            'supplier_burl' => $request->supplier_burl,
            'product_commend' => $request->product_commend,
            'product_status' => $request->product_status,
            'created_at' => date('Y-m-d H:i:s', time()),
        ];

        $lastId = DB::table('product')->insertGetId($arr);

        /*
         * 计算多个集合的笛卡尔积
         */
        if(isset($request->sp_val)) {
            $i=0;
            foreach ($request->sp_val as $key => $value) {
                $productAttrArr[$key]['product_id'] = $lastId;
                $productAttrArr[$key]['attr_id'] = $key;
                foreach ($value['attr_value'] as $k => $v) {
                    $productToAttrArr[$i]['attr_value_id'] = $k;
                    $productToAttrArr[$i]['attr_value_name'] = $v;
                    $productToAttrArr[$i]['product_id'] = $lastId;
                    $productToAttrArr[$i]['attr_id'] = $key;
                    $productToAttrArr[$i]['attr_name'] = $value['attr_name'];
                    $i++;
                }

            }
        }else{
            $productToAttrArr = '';
        }

        if(isset($request->sku)) {
            foreach ($request->sku as $key => $value) {
                $skuId = $spuId . str_pad($key, 4, '0', STR_PAD_LEFT);
                $skuArr[$key]['product_id'] = $lastId;
                $skuArr[$key]['sku_name'] = $request->product_name;
                $skuArr[$key]['sku_english'] = $request->product_english;
                $skuArr[$key]['sku_code'] = $skuId;
                $skuArr[$key]['sku_cost_price'] = $value['sku_cost_price'] == 0 ? $request->product_cost_price : $value['sku_cost_price'];
                $skuArr[$key]['sku_price'] = $value['sku_price'] == 0 ? $request->product_price : $value['sku_price'];
                $skuArr[$key]['sku_num'] = $value['sku_num'] != 0 ? $value['sku_num'] : 0;
                $skuArr[$key]['sku_image'] = $value['sku_image'];
                $skuArr[$key]['sku_attr_ids'] = $value['propids'];
                $skuArr[$key]['sku_attr_names'] = $value['propnames'];
                $skuArr[$key]['sku_attr_value_ids'] = $value['propvalids'];
                $skuArr[$key]['sku_attr_value_names'] = $value['propvalnames'];
                $skuAttrArr[] = $this->doProp($key, $skuId, $value['propids'], $value['propnames'], $value['propvalids'], $value['propvalnames']);
            }

            foreach ($skuAttrArr as $key => $val) {
                SkuAttrValue::insert($val);
            }
        }else{
            $skuArr = [];
            $productAttrArr = [];
            $productToAttrArr = [];
        }

        //数据插入
        ProductAttr::insert($productAttrArr);
        ProductToAttr::insert($productToAttrArr);
        $result = ProductGoods::insert($skuArr);
        return $result ? '0' : '1';
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = (new Category())->tree();
        $data = Product::find($id);
        $brand = Brand::get();
        $supplier = Supplier::get();

        return view('erp.product.edit', compact('data', 'category','brand','supplier'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //更新操作
        $result = Product::find($id);
        $product_spu = $result->product_spu;
        $result->product_name = $request->product_name;
        $result->product_english = $request->product_english;
        $result->brand_id = $request->brand_id;
        $result->product_barcode = $request->product_barcode;
        $result->product_cost_price = $request->product_cost_price;
        $result->product_price = $request->product_price;
        $result->product_freight = $request->product_freight;
        $result->product_size = $request->product_size;
        $result->product_weight = $request->product_weight;
        $result->product_image = $request->product_image;
        $result->product_content = $request->product_content;
        $result->supplier_id = $request->supplier_id;
        $result->supplier_bid = $request->supplier_bid;
        $result->supplier_url = $request->supplier_url;
        $result->supplier_burl = $request->supplier_burl;
        $result->product_commend = $request->product_commend;
        $result->product_status = $request->product_status;

        return $result->save() ? '0' : '1';
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除操作
        $result = Product::find($id);
        $result->ProductGoods()->delete();
        return $result->delete()?'0':'1';
    }

    /*
     * 创建SPU编号  8位  分类ID(2位)+年份(2位)+分类商品数量(4位)
     */
    public function createSpuCode($category_id){
        $product = Product::where('category_id',$category_id)->orderByDesc('product_code')->first();
        $category = Category::where('id',$category_id)->first();
        $category_code = $category->category_code;
        $yid = substr(date('Y'),-2);
        $codeLength = 4;
        $codeStr = 'Y';
        $subCode = str_pad('1',$codeLength,'0',STR_PAD_LEFT);
        if ($product) {
            $product_code = $product->product_code;
            if(strstr($product_code,$codeStr)){
                $code = substr($product_code,1);
            }else{
                $code = $product_code;
            }
            $number = intval(substr($code,strlen($category_id.$yid))) + 1;
            $subCode = str_pad($number,$codeLength,'0',STR_PAD_LEFT);
        }
        return $codeStr.$category_code . $yid . $subCode;
    }

    public function doProp($key,$skuId,$pIds,$pNames,$pValIds,$pValNames){
        $propIds = explode(',',$pIds);
        $propNames = explode(';',$pNames);
        $propValIds = explode(',',$pValIds);
        $propValNames = explode(';',$pValNames);
        foreach ($propIds as $k=>$v){
            $skuAttrArr[$k]['sku_id'] = $skuId;
            $skuAttrArr[$k]['attr_id'] = $v;
            $skuAttrArr[$k]['attr_name'] = $propNames[$k];
            $skuAttrArr[$k]['attr_value_id'] = $propValIds[$k];
            $skuAttrArr[$k]['attr_value_name'] = $propValNames[$k];
        }
        return $skuAttrArr;
    }

    /*
     * 正常php 处理二维数组中不需要的字段可能需要做遍历循环处理
     */
    public function doArr($data){
        $collection = collect($data);
        $collection = $collection->map(function ($item, $key) {
            return collect($item)->except(['aa', 'bb']);
        });
        $data = $collection->toArray();
        return $data;
    }

    /*
     * 显示产品下的SKU
     */
    public function sku($id)
    {
        //$data = ProductGoods::where('product_id',$id)->get();
        $data['id'] = $id;
        return view('erp.product.sku',compact('data'));
    }

    //编辑产品SKU
    public function sku_edit($id)
    {
        $data = Product::find($id);
        $attr = ProductAttr::with(['product_to_attr'=>function($query) use ($id){
            return $query->where('product_id',$id);
        },'attribute','attribute_value'])->where('product_id',$id)->orderBy('id','asc')->get();

        $goods = ProductGoods::where('product_id',$id)->orderBy('id','asc')->get();
        $sku_attr = [];
        $sku_value = [];
        foreach($goods as $key=>$value){
            //$sku_value[$key][] = $value['sku_attr_value_ids'];
            $sku_value[$value['sku_attr_value_ids']]['skuId'] = $value['id'];
            $sku_value[$value['sku_attr_value_ids']]['skuCostPrice'] = $value['sku_cost_price'];
            $sku_value[$value['sku_attr_value_ids']]['skuPrice'] = $value['sku_price'];
            $sku_value[$value['sku_attr_value_ids']]['skuStock'] = $value['sku_num'];
            $sku_value[$value['sku_attr_value_ids']]['skuImg'] = $value['sku_image'];
        }
        $goods_sku = json_encode($sku_value);

        return view('erp.product.sku_edit',compact('data','attr','goods_sku'));
    }


    //更新产品SKU
    public function sku_update(Request $request, $id)
    {
        //dd($request->sku);
        $product = Product::where('id',$id)->first();
        $spuId = $product->product_code;
        $skuNum = ProductGoods::where('product_id',$id)->count();
        /*
         * 计算多个集合的笛卡尔积
         */
        if(isset($request->sp_val)) {
            $i=0;
            foreach ($request->sp_val as $key => $value) {
                foreach ($value['attr_value'] as $k => $v) {
                    $productToAttrArr[$i]['attr_value_id'] = $k;
                    $productToAttrArr[$i]['attr_value_name'] = $v;
                    $productToAttrArr[$i]['product_id'] = $id;
                    $productToAttrArr[$i]['attr_id'] = $key;
                    $productToAttrArr[$i]['attr_name'] = $value['attr_name'];
                    $i++;
                }

            }
        }else{
            $productToAttrArr = '';
        }

        if(isset($request->sku)) {
            foreach ($request->sku as $key => $value) {
                if($value['sku_id'] != ''){
                    $skuArr[$key]['id'] = $value['sku_id'];
                    $skuArr[$key]['sku_cost_price'] = $value['sku_cost_price'] == 0 ? $product->product_cost_price : $value['sku_cost_price'];
                    $skuArr[$key]['sku_price'] = $value['sku_price'] == 0 ? $product->product_price : $value['sku_price'];
                    $skuArr[$key]['sku_num'] = $value['sku_num'] != 0 ? $value['sku_num'] : 0;
                    $skuArr[$key]['sku_image'] = $value['sku_image'];
                }else{
                    $skuId = $spuId . str_pad($skuNum, 4, '0', STR_PAD_LEFT);
                    $skuArr[$key]['product_id'] = $id;
                    $skuArr[$key]['sku_name'] = $product->product_name;
                    $skuArr[$key]['sku_english'] = $product->product_english;
                    $skuArr[$key]['sku_code'] = $skuId;
                    $skuArr[$key]['sku_cost_price'] = $value['sku_cost_price'] == 0 ? $product->product_cost_price : $value['sku_cost_price'];
                    $skuArr[$key]['sku_price'] = $value['sku_price'] == 0 ? $product->product_price : $value['sku_price'];
                    $skuArr[$key]['sku_num'] = $value['sku_num'] != 0 ? $value['sku_num'] : 0;
                    $skuArr[$key]['sku_image'] = $value['sku_image'];
                    $skuArr[$key]['sku_attr_ids'] = $value['propids'];
                    $skuArr[$key]['sku_attr_names'] = $value['propnames'];
                    $skuArr[$key]['sku_attr_value_ids'] = $value['propvalids'];
                    $skuArr[$key]['sku_attr_value_names'] = $value['propvalnames'];
                    $skuAttrArr[] = $this->doProp($key, $skuId, $value['propids'], $value['propnames'], $value['propvalids'], $value['propvalnames']);
                    $skuNum++;dd($skuAttrArr);
                    foreach ($skuAttrArr as $key => $val) {
                        //SkuAttrValue::insert($val);
                        SkuAttrValue::updateOrCreate();
                    }
                }

            }


        }else{
            $skuArr = [];
            $productAttrArr = [];
            $productToAttrArr = [];
        }

        dd($skuArr);



    }



}
