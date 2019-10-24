<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkuAttrValue extends Model
{
    //绑定数据库
    protected $table = "sku_attr_value";

    protected $guarded = [];

    public function attr_value(){
        return $this->belongsTo(AttributeValue::class,'attr_value_id')->withDefault();
    }
}
