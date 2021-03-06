<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttr extends Model
{
    //绑定数据库
    protected $table = "product_attr";

    protected $guarded = [];

    public function productToAttr(){
        return $this->hasMany('App\Models\ProductToAttr','attr_id','attr_id');
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class, 'attr_id','id');
    }

    public function attribute_value(){
        return $this->hasMany(AttributeValue::class, 'attr_id','attr_id');
    }

    public function product_to_attr(){
        return $this->hasMany(ProductToAttr::class, 'attr_id', 'attr_id');
    }
}
