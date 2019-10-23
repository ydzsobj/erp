<?php

namespace App\Http\Controllers\Erp;

use App\Events\OrderAuditSuccessed;
use App\Imports\OrdersImport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportOrderRequest;
use App\Models\Order;
use App\Models\OrderAuditLog;
use App\Models\ShopifyAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ShopifyAccountController extends Controller
{

    public function index(Request $request)
    {
        $countries = config('order.country_list');
        $status_list = config('order.status_list');
        return view('erp.shopify_account.index', compact('countries','status_list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = config('order.country_list');
        return view('erp.shopify_account.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $result = ShopifyAccount::create($data);

        $msg = $result ? '成功':'失败';

        return returned($result, $msg);
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
        $countries = config('order.country_list');
        $detail = ShopifyAccount::find($id);
        return view('erp.shopify_account.edit', compact('detail','countries'));
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
        $data = $request->except('_token');

        // dd($data);

        $shopify_account = ShopifyAccount::find($id);

        $result = $shopify_account->where('id', $id)->update($data);

        $msg = $result ? '更新成功':'更新失败';

        return returned($result, $msg);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = ShopifyAccount::where('id', $id)->delete();

        $msg = $result ? '成功':'失败';

        return returned($result, $msg);

    }

    //抓取订单
    public function create_order(Request $request){

        $id = $request->post('id');

        $shopify_account = ShopifyAccount::find($id);

        list($success,$msg) = $shopify_account->create_order();

        return returned($success, $msg);

    }


}
