<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAuditLog extends Model
{
    protected $table = 'order_audit_logs';

    protected $fillable = [
        'order_id',
        'reamrk',
        'admin_id',
    ];

    public function admin_user(){
        return $this->belongsTo(Admin::class,'admin_id')->withDefault();
    }
}
