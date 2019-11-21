<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    //绑定数据库
    use SoftDeletes;

    protected $table = 'attribute_value';
    protected $dates = ['deleted_at'];

    protected $guarded = ['LAY_TABLE_INDEX','tempId'];

}
