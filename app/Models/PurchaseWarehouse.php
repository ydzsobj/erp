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

    //仓库
    public function warehouse(){
        return $this->hasOne('App\Models\Warehouse','id','warehouse_id');
    }

}
