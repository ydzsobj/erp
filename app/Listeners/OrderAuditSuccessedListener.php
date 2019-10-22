<?php

namespace App\Listeners;

use App\Events\OrderAuditSuccessed;
use App\Models\OrderAuditLog;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderAuditSuccessedListener
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
     * @param  OrderAuditSuccessed  $event
     * @return void
     */
    public function handle(OrderAuditSuccessed $event)
    {
        //添加订单的操作日志
        $order_ids = $event->order_ids;

        $log_data = collect([]);

        $admin_id = Auth::user()->admin_id;

        foreach($order_ids as $order_id){
            $data = $log_data->push([
                    'order_id' => $order_id,
                    'remark' => $event->remark,
                    'admin_id' => $admin_id,
                    'created_at' => Carbon::now()
                ]
            );
        }

        DB::table('order_audit_logs')->insert($data->all());
    }
}
