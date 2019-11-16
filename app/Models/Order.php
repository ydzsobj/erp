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


    public function search($request){

        $keywords = $request->get('keywords');
        $status = $request->get('order_status');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        return $orders = static::where('order_status','1');
            //->keywords($keywords)
            //->status($status)
            //->date($start_date, $end_date)
            //->select('orders.*')
            //->orderBy('id','desc')
            //->offset(($page-1)*$limit)
            //->limit($limit);

        $search = compact('page');

        return [$orders, $search ];
    }

    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;

    }

    //状态搜索
    public function scopeStatus(){

    }

    //时间搜索
    public function scopeDate(){

    }


    //
    public function order_info(){
        return $this->hasMany(OrderInfo::class,'order_id','id');
    }

    public function order_sn($order_sn){
        return self::where('order_sn', $order_sn)->first();
    }



}
