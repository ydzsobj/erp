<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryImportLog extends Model
{
    protected $table = 'inventory_import_logs';
    const TABLE = 'inventory_import_logs';

    protected $page_size = 50;

    protected $fillable = [
        'import_nums',
        'warehouse_id',
        'admin_id',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    function get_data($request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $warehouse_id = $request->get('warehouse_id');
        return self::with(['admin'])
            ->when($warehouse_id, function($query) use ($warehouse_id){
                $query->where('warehouse_id', $warehouse_id);
            })
            ->when($start_date && $end_date, function($query) use($start_date, $end_date){
                $query->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->page_size);
    }
}
