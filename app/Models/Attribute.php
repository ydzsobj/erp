<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Attribute extends Model
{
    //绑定数据库
    use Notifiable;

    protected $table = 'attribute';
    protected $dates = ['deleted_at'];

    //一对一关联
    public function type()
    {
        return $this->hasOne('App\Models\Type','id','type_id');
    }

    //一对多关联
    public function attributeValue()
    {
        return $this->hasMany('App\Models\AttributeValue','id','attr_id');
    }

}
