<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = "product";
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    //一对一关联
    public function category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function productToAttr(){
        return $this->hasMany('App\Models\ProductToAttr','product_id','id');
    }

    public function productAttr(){
        return $this->hasMany('App\Models\ProductAttr','product_id','id');
    }

}
