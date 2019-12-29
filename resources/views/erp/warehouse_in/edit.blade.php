@extends('erp.father.father')
@section('content')
    <div class="layui-fluid layui-card">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <input type="hidden" name="purchase_warehouse_id" value="{{$data->id}}"/>
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
                        <select name="supplier_id" disabled>
                            <option value="0">请选择供应商</option>
                            @foreach($supplier as $value)
                                <option value="{{$value->id}}" @if($value->id==$data->supplier_id) selected @endif>{{$value->supplier_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">入库仓库</label>
                    <div class="layui-input-inline">
                        <select name="warehouse_id" lay-verify="required" disabled>
                            <option value="0">请选择入库仓库</option>
                            @foreach($warehouse as $value)
                                <option value="{{$value->id}}" @if($value->id==$data->warehouse_id) selected @endif>{{$value->warehouse_name}}</option>
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

            form.val('formData', {
                "warehouse_text": "{{$data->warehouse_text}}"

            });


            var tableIns = table.render({
                elem: '#dataTable',
                height: 512,
                url: "{{url('api/purchase_warehouse/balance')}}/{{$data->id}}", //数据接口
                cols: [[
                    {title: '序号', type: 'numbers'},
                    {field: 'id', title: 'ID', event:'modify', width: 100},
                    {field: 'goods_sku', title: 'SKU编码',  edit: 'text', width: 150},
                    {field: 'goods_name', title: '商品名称', width: 220},
                    {field: 'goods_attr_name', title: '属性名', width: 120},
                    {field: 'goods_attr_value', title: '属性值', width: 120},
                    {field: 'goods_num', title: '采购数量', width: 100},
                    {field: 'balance_num', title: '差额数量', width: 100},
                    {field: 'order_num', title: '订单数量', width: 100},
                    {field: 'plan_num', title: '备货数量', width: 100},
                    {field: 'goods_money', title: '金额', width: 120},

                    {field: 'real_num', title: '验收数量', edit:'text', width: 100, fixed: 'right'},
                ]],
            });

            function isNumber(val){

                var regPos = /^\d+(\.\d+)?$/; //非负浮点数
                var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
                if(regPos.test(val) || regNeg.test(val)){
                    return true;
                }else{
                    return false;
                }

            }


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

                    if(!row.id || !isNumber(row.real_num) || row.real_num<0 || row.real_num=='' || row.real_num == null){
                        layer.msg("检查每一行，请输入有效验收数量！", { icon: 5 }); //提示
                        return false;
                    }

                    if(row.real_num>row.goods_num){
                        layer.msg("提交数量大于差额数量，多出商品数量请另行入库！", { icon: 5 }); //提示
                        return false;
                    }

                }
                $.ajax({
                    url:"{{url('admins/warehouse_in/'.$data->id)}}",
                    type:'put',
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
                            layer.msg(msg.msg,{icon:2,time:3000});
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
