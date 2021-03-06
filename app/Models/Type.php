<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    //绑定模型
    use SoftDeletes;

    protected $table = "type";
    protected $dates = ['deleted_at'];


    //一对多
    public function attribute(){
        return $this->hasMany('App\Models\attribute','id','type_id');
    }

}
