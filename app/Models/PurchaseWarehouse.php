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
        return $this->hasOne(Warehouse::class,'id','warehouse_id');
    }

    public function purchase_order_warehouse(){
        return $this->hasOne(PurchaseOrderWarehouse::class,'purchase_warehouse_id','id');
    }

}
