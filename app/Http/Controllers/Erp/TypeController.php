<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypeRequest;
use App\Models\Attribute;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.type.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        //$attribute = Attribute::where('attr_show',1)->get();
        //return view('erp.type.create',compact('attribute'));
        return view('erp.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeRequest $request)
    {
        //存储表单信息
        $result = Type::insert([
            'type_name'=>$request->type_name,
            'type_english'=>$request->type_english,
            //'attr_ids'=>implode(",", $request->attr_ids),
            'type_sort'=>$request->type_sort,
            'created_at' => date('Y-m-d H:i:s', time()),
        ]);
        return $result ? '0' : '1';
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
        //编辑操作
        $attribute = Attribute::where('type_id',$id)->get();
        $data = Type::find($id);
        //$dataArray = explode(',',$data->attr_ids);
        //return view('erp.type.edit',compact('data','attribute','dataArray'));
        return view('erp.type.edit',compact('data','attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeRequest $request, $id)
    {
        //更新操作
        $result = Type::find($id);
        $result->type_name = $request->type_name;
        $result->type_english = $request->type_english;
        //$result->attr_ids = implode(",", $request->attr_ids);
        $result->type_sort = $request->type_sort;
        return $result->save()?'0':'1';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //删除操作
        $result = Type::find($id);
        return $result->delete()?'0':'1';
    }
}
