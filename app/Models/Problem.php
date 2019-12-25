<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    //
    protected $table = "problem";

    protected $guarded = [];


    /*
     * 验收入库
     */
    public function searchPurchaseWarehouse($request,$id=''){
        $keywords = $request->get('keywords')?:'';

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::with('purchase_warehouse_info')->where('order_type','2')
            ->keywords($keywords)
            ->date($start_date, $end_date)
            ->count();
        $orders = static::with('purchase_warehouse_info')->where('order_type','2')
            ->keywords($keywords)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }


    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;
        return $query->where('order_sn','like',"%{$keywords}%")->orWhere('yunlu_sn','like',"%{$keywords}%")
            ->orWhere('order_name','like',"%{$keywords}%")->orWhere('order_address','like',"%{$keywords}%")
            ->orWhere('order_phone','like',"%{$keywords}%")->orWhere('id','like',"%{$keywords}%");
    }

    //时间搜索
    public function scopeDate($query, $start_date,$end_date){
        if(!$start_date || !$end_date) return $query;
        $query->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function purchase_warehouse_info(){
        return $this->belongsTo(PurchaseWarehouseInfo::class,'relate_id','id');
    }


}
