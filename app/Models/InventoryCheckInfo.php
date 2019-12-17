<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCheckInfo extends Model
{
    //绑定数据库

    protected $table = "inventory_check_info";

    public function inventory_check(){
        return $this->belongsTo(InventoryCheck::class,'inventory_check_id','id');
    }

    //拣货单搜索
    public function search($request,$id){


        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;
        $count = static::where('inventory_check_id',$id)
            ->count();
        $orders = static::where('inventory_check_id',$id)
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();


        return [$orders,$count];
    }

}
