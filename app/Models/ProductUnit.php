<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnit extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "product_unit";
    protected $dates = ['deleted_at'];

}
