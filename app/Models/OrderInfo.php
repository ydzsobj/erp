<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    //
    protected $table = "order_info";

    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function product_goods(){
        return $this->belongsTo(ProductGoods::class,'goods_sku','sku_code');
    }


}
