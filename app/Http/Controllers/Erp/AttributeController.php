<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeRequest;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Type;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //首页列表
        return view('erp.attribute.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //创建操作
        $type = Type::orderByDesc('id')->get();
        return view('erp.attribute.create',compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeRequest $request)
    {
        //存储表单信息
        $result = Attribute::insert([
            'attr_name'=>$request->attr_name,
            'attr_english'=>$request->attr_english,
            'type_id'=>$request->type_id,
            'attr_value_ids'=>'',
            'attr_status'=>$request->attr_status,
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
        $type = Type::get();
        $attribute_value = AttributeValue::where('attr_id',$id)->get();
        $data = Attribute::find($id);
        $dataArray = explode(',',$data->attr_value_ids);
        return view('erp.attribute.edit', compact('data','type','attribute_value','dataArray'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeRequest $request, $id)
    {
        $table = $request->table;  //获取信息

        //更新操作
        $result = Attribute::find($id);
        if(isset($table['AttrValue'])){
            foreach ($table['AttrValue'] as $key=>$value){
                AttributeValue::updateOrCreate(['id'=>$value['id']],$value);
            }
        };

        $result->attr_name = $request->attr_name;
        $result->attr_english = $request->attr_english;
        $result->attr_status = $request->attr_status;
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
        $result = Attribute::find($id);
        return $result->delete()?'0':'1';
    }
}
