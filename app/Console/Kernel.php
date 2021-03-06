<?php

namespace App\Console;

use App\Console\Commands\ImportShopifyOrder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\OrderLock::class,
        //ImportShopifyOrder::class,   //抓取订单
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
         $schedule->command('order:lock')
             ->everyMinute();
        //抓取订单
//        $import_order_log_path = storage_path('logs/import_order.log');
//        $schedule->command('import:order')->everyFiveMinutes()->appendOutputTo($import_order_log_path);;
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
