<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class ShopifyApi extends Model
{

    private $api_key;
    private $api_secret;
    private $api_domain;
    private $shopify_account;

    public function __construct($shopify_account)
    {
        $this->shopify_account = $shopify_account;
        $this->api_key = $shopify_account->api_key;
        $this->api_secret = $shopify_account->api_secret;
        $this->api_domain = $shopify_account->api_domain;
    }

    public function orders(){

        $client = new Client();

        $api = '/admin/api/2019-10/orders.json';
        $request_url = 'https://'. $this->api_key.':'. $this->api_secret. '@'. $this->api_domain. $api;
        Log::info('请求:'.$request_url);

        try {
            $response = $client->request('GET', $request_url);
            if($response->getStatusCode() == 200){
                if($response->getBody()){
                    $data = json_decode($response->getBody(),true);
                    return [$data, '获取数据成功'];
                }else{
                    return [false, '店铺id='. $this->shopify_account->id. ' 返回内容不存在'];
                }
            }else{
                return [false, '店铺id='. $this->shopify_account->id.' 请求失败，状态码：'. $response->getStatusCode()];
            }


        } catch (RequestException $e) {
            Log::info('店铺id='. $this->shopify_account->id.' 请求接口:'.$request_url.'失败');
            if ($e->hasResponse()) {
                Log::info($e->getResponse());
            }
        }
    }


}
