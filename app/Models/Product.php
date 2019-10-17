<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "product";
    protected $dates = ['deleted_at'];
    protected $guarded = [];

}
