<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    //绑定数据库
    use SoftDeletes;

    protected $table = 'admin';
    protected $dates = ['deleted_at'];


}
