@extends('erp.father.father')
@section('content')
    <div class="layui-fluid layui-card">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <fieldset class="layui-elem-field layui-field-title">
                <legend>采购需求配置单</legend>
            </fieldset>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">订单编号</label>
                    <div class="layui-input-inline">
                        <div class="layui-form-mid" style="color: #ff0000">* 订单编号自动生成</div>
                    </div>
                    <label class="layui-form-label">供应商家</label>
                    <div class="layui-input-inline">
                        <select name="supplier_id">
                            <option value="0">请选择供应商</option>
                            @foreach($supplier as $value)
                                <option value="{{$value->id}}">{{$value->supplier_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label" style="width: 90px">预计出货日期</label>
                        <div class="layui-input-inline">
                            <input type="text" name="expect_out_at" autocomplete="off" lay-verify="required" class="layui-input" id="dateTime" placeholder="yyyy-MM-dd HH:mm:ss">
                        </div>
                    </div>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">付款方式</label>
                <div class="layui-input-inline">
                    <select name="payment_type">
                        <option value="0">记应付账款</option>
                    </select>
                </div>
                <label class="layui-form-label">采购备注</label>
                <div class="layui-input-inline">
                    <input type="text" name="purchase_text" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label" style="width: 90px">预计到货日期</label>
                    <div class="layui-input-inline">
                        <input type="text" name="expect_deliver_at" autocomplete="off" lay-verify="required" class="layui-input" id="dateTime2" placeholder="yyyy-MM-dd HH:mm:ss">
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
                ,layer = layui.layer;
            var $=layui.jquery;

            //日期时间选择器
            laydate.render({
                elem: '#dateTime'
                ,type: 'datetime'
            });
            laydate.render({
                elem: '#dateTime2'
                ,type: 'datetime'
            });



            var parent_json = eval('('+parent.json+')');

            var tableIns = table.render({
                elem: '#dataTable',
                height: 512,
                data: parent_json.data,
                cols: [[
                    //{title: '序号', type: 'numbers'},
                    {field: 'id', title: 'ID', totalRow: true, width: 80},
                    {field: 'goods_sku', title: 'SKU编码', totalRow: true, width: 135},
                    {field: 'goods_name', title: '商品名称', width: 180},
                    {field: 'goods_attr_name', title: '属性名', width: 100},
                    {field: 'goods_attr_value', title: '属性值', width: 100},
                    {field: 'plan_num', title: '备货数量', edit:'text', width: 100},
                    {field: 'order_num', title: '订单数量', edit:'text', width: 100},
                    {field: 'goods_money', title: '金额', edit:'text', width: 100},
                    // {field: 'tax_rate', title: '税率', edit:'text', width: 80},
                    // {field: 'tax', title: '税费', width: 100},
                    // {field: 'money_tax', title: '税金小计', width: 100},
                    //{toolbar: '#table_tool', title: '操作', fixed: 'right', align: 'center', width: 180}
                ]],
            });


            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                data.field.table = table.cache;
                for(var i=0, row; i < table.cache.dataTable.length; i++){
                    row = table.cache.dataTable[i];
                    if(row.order_num==0 || row.order_num==''){
                        layer.msg("检查每一行，请完善数据！", { icon: 5 }); //提示
                        return false;
                    }
                }
                $.ajax({
                    url:"{{url('/admins/purchase_pool')}}",
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
