<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "order";
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    //
    public function order_info(){
        return $this->hasMany(OrderInfo::class,'order_id','id');
    }

    public function order_sn($order_sn){
        return self::where('order_sn', $order_sn)->first();
    }



}
