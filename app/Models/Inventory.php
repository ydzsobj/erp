<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    //绑定数据库

    protected $table = "inventory";

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['LAY_TABLE_INDEX','tempId'];
}
