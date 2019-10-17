<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGoods extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "product_goods";
    protected $dates = ['deleted_at'];
    protected $guarded = [];

}
