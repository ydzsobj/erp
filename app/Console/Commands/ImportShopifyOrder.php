<?php

namespace App\Console\Commands;

use App\Models\ShopifyAccount;
use App\Models\ShopifyApi;
use App\Models\ShopifyOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportShopifyOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '抓取所有shopify店铺的订单数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取所有店铺账号
        $shopify_accounts = ShopifyAccount::all();

        $successed = 0;
        $failed = 0;
        $msg_content = '';

        $shopify_order = new ShopifyOrder();

        foreach($shopify_accounts as $shopify_account){

            list($success, $msg) = $shopify_order->create_order($shopify_account);

            if($success){
                $successed++;
            }else{
                $failed++;
            }

            $msg_content .= $msg. "\n";
        }

        echo '['. Carbon::now() .']';
        echo "共抓取 {$shopify_accounts->count()}个店铺账号，成功：{ $successed } 个；失败： { $failed } 个;\n";
        echo $msg_content;
    }
}
