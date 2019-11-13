<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGoods extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = 'product_goods';

    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function sku_values(){
        return $this->hasMany(SkuAttrValue::class,'sku_id','sku_code');
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    /**
     * @产品sku列表
     */
    static public function product_skus($product_id){
        return self::where('product_id', $product_id)->select('id','sku_attr_value_names','sku_code')->get();
    }
}
