<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseWarehouse extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "purchase_warehouse";
    protected $dates = ['deleted_at'];
}
