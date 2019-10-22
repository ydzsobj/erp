<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSku extends Model
{
    protected $table = 'order_skus';

    protected $fillable = [
        'order_id',
        'sku_id',
        'sku_nums',
    ];

    public function sku(){
        return $this->belongsTo(ProductGoods::class,'sku_id', 'id')->withDefault();
    }
}
