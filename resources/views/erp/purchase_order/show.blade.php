@extends('erp.father.father')
@section('content')
    <div class="layui-fluid layui-card">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <input type="hidden" name="purchase_order_id" value="{{$id}}"/>
            <fieldset class="layui-elem-field layui-field-title">
                <legend>采购入库单</legend>
            </fieldset>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">订单编号</label>
                    <div class="layui-form-mid" style="color: #ff0000">* 订单编号自动生成</div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">自动拆分</label>
                    <div class="layui-input-inline">
                        <select name="">
                            <option value="0">不自动拆分业务</option>
                            <option value="1">自动拆分业务</option>
                        </select>
                    </div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">供应商</label>
                    <div class="layui-input-inline">
                        <select name="supplier_id">
                            <option value="0">请选择供应商</option>
                            @foreach($supplier as $value)
                                <option value="{{$value->id}}">{{$value->supplier_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">入库仓库</label>
                    <div class="layui-input-inline">
                        <select name="warehouse_id" lay-verify="required">
                            <option value="">请选择入库仓库</option>
                            @foreach($warehouse as $value)
                                <option value="{{$value->id}}">{{$value->warehouse_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="layui-form-label">付款方式</label>
                    <div class="layui-input-inline">
                        <select name="payment_type">
                            <option value="0">记应付账款</option>
                        </select>
                    </div>
                    <label class="layui-form-label">备注</label>
                    <div class="layui-input-inline">
                        <input type="text" name="warehouse_text" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                <legend>采购商品详情</legend>
            </fieldset>


            <div id="dataTable" lay-filter="dataTable"></div>
            <script type="text/html" id="table_tool">
                <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
                <a class="layui-btn layui-btn-xs" lay-event="add_1">add-↑</a>
                <a class="layui-btn layui-btn-xs" lay-event="add_2">add-↓</a>
            </script>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="form">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>

    </div>


@endsection
@section('js')
    <script>
        //Demo
        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).use(['form','upload','laydate','table','layer'], function(){
            var form = layui.form
                ,laydate = layui.laydate
                ,table = layui.table
                ,layer = layui.layer
                ,upload = layui.upload;
            var $=layui.jquery;

            //日期时间选择器
            laydate.render({
                elem: '#dateTime'
                ,type: 'datetime'
            });



            var tableIns = table.render({
                elem: '#dataTable',
                url: "{{url('api/purchase_order/goods')}}/{{$id}}", //数据接口
                totalRow: true,
                height: 512
                ,cols: [[
                    {field:'id', title: 'ID', width:80, sort: true}
                    ,{field:'goods_id', title: 'SKU ID', width:100, sort: true}
                    ,{field:'sku_name', title: '商品名称', width:180,templet:function(res){return res.goods_name;}}
                    ,{field:'goods_attr_name', title: '属性名', width:100}
                    ,{field:'goods_attr_value', title: '属性值', width:100}
                    ,{field:'plan_num', title: '备货数量', width: 100}
                    ,{field:'order_num', title: '订单数量', width: 100}
                    ,{field:'goods_money', title: '金额', width: 100}
                    ,{field:'goods_sku', title: '商品编码', width:135, fixed: 'right'}
                ]]
            });


            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                data.field.table = table.cache;
                for(var i=0, row; i < table.cache.dataTable.length; i++){
                    row = table.cache.dataTable[i];
                    if(!row.id || row.goods_num==0 || row.goods_num==''){
                        layer.msg("检查每一行，请完善数据！", { icon: 5 }); //提示
                        return false;
                    }
                }
                $.ajax({
                    url:"{{url('admins/purchase_warehouse/add')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg=='0'){
                            layer.msg('添加成功！',{icon:1,time:2000},function () {
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg('添加失败！',{icon:2,time:2000});
                        }
                    },
                    error: function(data){
                        var errors = JSON.parse(data.responseText).errors;
                        var msg = '';
                        for(var a in errors){
                            msg += errors[a][0]+'<br />';
                        }
                        layer.msg(msg,{icon:2,time:2000});
                    }
                });
                return false;
            });



        });
    </script>
@endsection
