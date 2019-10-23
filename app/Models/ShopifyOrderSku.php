<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyOrderSku extends Model
{
    protected $table = 'shopify_order_skus';

    protected $fillable = [
        'order_id',
        'sku_id',
        'sku_nums',
        'price',
    ];

    public function sku(){
        return $this->belongsTo(ProductGoods::class,'sku_id', 'sku_code')->withDefault();
    }
}
