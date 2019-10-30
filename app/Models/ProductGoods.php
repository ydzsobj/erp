<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductGoods extends Model
{
    //
    protected $table = 'product_goods';

    public function sku_values(){
        return $this->hasMany(SkuAttrValue::class,'sku_id','sku_code');
    }

    /**
     * @产品sku列表
     */
    static public function product_skus($product_id){
        return self::where('product_id', $product_id)->select('id','sku_attr_value_names','sku_code')->get();
    }
}
