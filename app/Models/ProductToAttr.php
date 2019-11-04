<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductToAttr extends Model
{
    //绑定数据库
    protected $table = 'product_to_attr';


    public function productAttr(){
        return $this->belongsTo('App\Models\ProductAttr','attr_id','attr_id');
    }

}
