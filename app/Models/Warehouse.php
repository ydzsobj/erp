<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "warehouse";
    protected $dates = ['deleted_at'];

    /**
     * 印尼仓
     */
    const YN_WAREHOUSE_ID = 3;
    /**
     * 印尼虚拟仓
     */
    const YN_VIRTUAL_WAREHOUSE_ID = 4;
}
