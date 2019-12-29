<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseIn extends Model
{
    //
    protected $table = "warehouse_in";

    protected $guarded = [];

    //搜索
    public function search($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('status')?:0;
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::where('warehouse_in_code','!=','')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $data = static::where('warehouse_in_code','!=','')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$data,$count];
    }

    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;
        return $query->where('order_sn','like',"%{$keywords}%")->orWhere('yunlu_sn','like',"%{$keywords}%")
            ->orWhere('order_name','like',"%{$keywords}%")->orWhere('order_address','like',"%{$keywords}%")
            ->orWhere('order_phone','like',"%{$keywords}%")->orWhere('id','like',"%{$keywords}%");
    }

    //订单状态搜索
    public function scopeStatus($query, $status){
        if(!$status) return $query;
        return $query->where('purchase_warehouse_status',$status);
    }

    //仓库状态搜索
    public function scopeWarehouse($query, $warehouse_id){
        if(!$warehouse_id) return $query;
        return $query->where('warehouse_id',$warehouse_id);
    }

    //时间搜索
    public function scopeDate($query, $start_date,$end_date){
        if(!$start_date || !$end_date) return $query;
        $query->whereBetween('created_at', [$start_date, $end_date]);
    }


}
