<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderLock extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "order_lock";
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    //一对一关联

}
