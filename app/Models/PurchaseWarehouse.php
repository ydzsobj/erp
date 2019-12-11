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


    //搜索
    public function search($request,$id=''){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('order_status')?:0;
        $warehouse_id = $id??'';
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $count = static::where('purchase_warehouse_code','!=','')
            ->keywords($keywords)
            ->status($status)
            ->warehouse($warehouse_id)
            ->date($start_date, $end_date)
            ->count();
        $data = static::where('purchase_warehouse_code','!=','')
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
