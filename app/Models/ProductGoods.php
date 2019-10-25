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
}
