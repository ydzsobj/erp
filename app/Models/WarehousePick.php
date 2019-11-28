<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehousePick extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "warehouse_pick";
    protected $dates = ['deleted_at'];

    //搜索
    public function search($request){

        $keywords = $request->get('keywords')?:'';
        $status = $request->get('pick_status')?:0;
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $page = $request->page ?: 1;
        $limit = $request->limit ?: 100;

        $result = static::with(['warehouse'])->where('pick_status',$status)
            ->keywords($keywords)
            //->status($status)
            ->date($start_date, $end_date)
            //->select('orders.*')
            ->orderBy('id','desc')
            ->offset(($page-1)*$limit)
            ->limit($limit)
            ->get();

        return $result;
    }

    //关键词搜索
    public function scopeKeyWords($query, $keywords){
        if(!$keywords) return $query;
        return $query->where('pick_code','like',"%{$keywords}%")->orWhere('pick_name','like',"%{$keywords}%")
            ->orWhere('pick_phone','like',"%{$keywords}%")->orWhere('id','like',"%{$keywords}%");
    }

    //时间搜索
    public function scopeDate($query, $start_date,$end_date){
        if(!$start_date || !$end_date) return $query;
        $query->whereBetween('created_at', [$start_date, $end_date]);
    }

    public function warehouse(){
        return $this->hasOne(Warehouse::class,'id','warehouse_id');
    }


}
