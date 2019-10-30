<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryTransfer extends Model
{
    //绑定数据库

    protected $table = "inventory_transfer";

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
