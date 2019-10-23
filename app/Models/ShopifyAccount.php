<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopifyAccount extends Model
{
    protected $table = 'shopify_accounts';

    protected $fillable = [
        'api_key',
        'api_secret',
        'api_domain',
    ];
}
