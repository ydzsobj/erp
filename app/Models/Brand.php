<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "brand";
    protected $dates = ['deleted_at'];

}
