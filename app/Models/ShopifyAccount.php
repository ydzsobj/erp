<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ShopifyAccount extends Model
{
    protected $table = 'shopify_accounts';

    use SoftDeletes;

    protected $fillable = [
        'api_key',
        'api_secret',
        'api_domain',
        'name',
        'country_id'
    ];

    public function get_data($request){

        $keywords = $request->get('keywords');

        return self::ofKeywords($keywords)->paginate(10);
    }

    public function scopeOfKeywords($query, $keywords){
        if($keywords){
            $query->where(function($sub_query) use ($keywords){
                $sub_query->where('name', 'like', '%'. $keywords. '%')
                    ->orWhere('id', $keywords);
            });
        }else{
            return $query;
        }
    }

}
