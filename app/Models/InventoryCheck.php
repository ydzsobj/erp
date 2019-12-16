<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryCheck extends Model
{
    //绑定数据库

    protected $table = "inventory_check";

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    //拣货单搜索
    public function search($request,$id=''){

        $warehouse_id = $id??'';

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::warehouse($warehouse_id)
            ->count();
        $orders = static::warehouse($warehouse_id)
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

    //仓库状态搜索
    public function scopeWarehouse($query, $warehouse_id){
        if(!$warehouse_id) return $query;
        return $query->where('warehouse_id',$warehouse_id);
    }



}
