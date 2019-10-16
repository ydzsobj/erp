<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "supplier";
    protected $dates = ['deleted_at'];
}
