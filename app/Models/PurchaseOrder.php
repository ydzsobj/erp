<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "purchase_order";
    protected $dates = ['deleted_at'];
}
