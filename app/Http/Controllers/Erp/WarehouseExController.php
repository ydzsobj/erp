<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Imports\ExImport;
use App\Imports\OrderImport;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class WarehouseExController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('erp.warehouse_ex.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('erp.warehouse_ex.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $filename = $request->upload_file;
        $import = new ExImport();

        $collection = Excel::import($import, $filename);
        //$collection = Excel::toCollection($import, $filename);
        //dd($collection);
        $msg = session()->get('excel');
        return response()->json(['code'=>'0','msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @批量生成拣货单
     */
    public function createEx(Request $request){
        $ids = $request->get('ids');
        $ids=explode(',',$ids);
        $data = [
            'ex_at' => Carbon::now(),
            'ex_status' => 1,
            'ex_id' => Auth::user()->id
        ];
        $res = Order::whereIn('id', $ids)->update($data);
        return $res ? '0':'1';
    }


}
