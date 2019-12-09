<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted  $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        if(env('APP_ENV','local') == 'local'){
            if($event->sql){
                // 把sql  的日志独立分开
                // $fileName = storage_path('logs/sql/'.date('Y-m-d').'.log');
                // Log::useFiles($fileName,'info');
                $sql = str_replace("?", "'%s'", $event->sql);
                $log = vsprintf($sql, $event->bindings);
                Log::info($log);
            }
        }
    }
}
