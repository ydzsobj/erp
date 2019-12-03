<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderInfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OrderLock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:lock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '运单锁定';

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
        //
        $order = Order::with('order_info')->where('order_lock',0)->orderBy('id','asc')->get();
        foreach ($order as $key=>$value){
            foreach($value['order_info'] as $k=>$v){
                if($v['goods_lock']=='1'){
                    continue;
                }else{

                }
            }
        }

        //$order_info = OrderInfo::where('goods_lock','!=','1')->orderBy('id','desc')->get();
        Log::info('订单锁定');
    }
}
