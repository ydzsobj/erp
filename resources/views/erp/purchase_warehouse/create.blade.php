@extends('erp.father.father')
@section('content')
    <div class="layui-fluid layui-card">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <fieldset class="layui-elem-field layui-field-title">
                <legend>采购入库单</legend>
            </fieldset>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">订单编号</label>
                    <div class="layui-form-mid" style="color: #ff0000">* 订单编号自动生成</div>
                    <div class="layui-form-mid"></div>
                    <div class="layui-inline">
                        <label class="layui-form-label">入库日期</label>
                        <div class="layui-input-inline">
                            <input type="text" name="deliver_at" class="layui-input" id="dateTime" placeholder="yyyy-MM-dd HH:mm:ss">
                        </div>
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
                        <select name="supplier_id">
                            <option value="0">请选择入库仓库</option>
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
                        <input type="text" name="purchase_text" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
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
                totalRow: true,
                height: 512,
                data: [
                    {},
                ],
                page: {},
                cols: [[
                    {title: '序号', type: 'numbers'},
                    {field: 'id', title: 'ID',  edit: 'text',totalRow: true,event:'modify', width: 80},
                    {field: 'goods_sku', title: 'SKU编码',  edit: 'text',totalRow: true, width: 135},
                    {field: 'sku_name', edit: true, title: '商品名称', width: 180},
                    {field: 'goods_attr_name', title: '属性名', width: 100},
                    {field: 'goods_attr_value', title: '属性值', width: 100},
                    {field: 'goods_price', title: '销售价', width: 100},
                    {field: 'goods_num', title: '数量', edit:'text', width: 80},
                    {field: 'goods_money', title: '金额', width: 100},
                    {field: 'tax_rate', title: '税率', edit:'text', width: 80},
                    {field: 'tax', title: '税费', width: 100},
                    {field: 'money_tax', title: '税金小计', width: 100},
                    {toolbar: '#table_tool', title: '操作', fixed: 'right', align: 'center', width: 180}
                ]],
            });

            // 工具监听
            table.on('tool(dataTable)', function (obj) {
                console.log(obj)
                switch (obj.event) {
                    case 'del':
                        var tableObj = tableIns;
                        var config = tableObj.config;
                        var dataTemp = config.data;
                        var page = config.page;
                        var trElem = obj.tr.first();
                        var index = trElem.data('index');
                        var dataIndex = index + page.limit * (page.curr - 1);
                        dataTemp.splice(dataIndex, 1);
                        // 新的页数
                        var pageNew = Math.ceil(dataTemp.length / page.limit);
                        // 重新接收reload返回的对象，这个很重要
                        tableIns = table.reload(config.id, $.extend(true, {
                            // 更新数据
                            data: dataTemp
                        }, config.page ? {
                            // 如果删除了数据之后页数变了，打开前一页
                            page: {
                                curr: page.curr > pageNew ? ((page.curr - 1) || 1) : page.curr
                            }
                        } : {}));
                        break;
                    case 'add_1':
                        var tableObj = tableIns;
                        var config = tableObj.config;
                        var dataTemp = config.data;
                        var page = config.page;
                        var trElem = obj.tr.first();
                        var index = trElem.data('index');
                        var dataIndex = index + page.limit * (page.curr - 1);
                        dataTemp.splice(dataIndex, 0,{id: ''});
                        var pageNew = Math.ceil(dataTemp.length / page.limit);
                        tableIns = table.reload(config.id, $.extend(true, {
                            // 更新数据
                            data: dataTemp
                        }, config.page ? {
                            page: {
                                curr: page.curr > pageNew ? ((page.curr - 1) || 1) : page.curr
                            }
                        } : {}));
                        break;
                    case 'add_2':
                        var tableObj = tableIns;
                        var config = tableObj.config;
                        var dataTemp = config.data;
                        var page = config.page;
                        var trElem = obj.tr.first();
                        var index = trElem.data('index');
                        var dataIndex = index + page.limit * (page.curr - 1);
                        dataTemp.splice(dataIndex+1, 0,{id: ''});
                        // 新的页数
                        var pageNew = Math.ceil(dataTemp.length / page.limit);
                        // 重新接收reload返回的对象，这个很重要
                        tableIns = table.reload(config.id, $.extend(true, {
                            // 更新数据
                            data: dataTemp
                        }, config.page ? {
                            page: {
                                curr: page.curr > pageNew ? ((page.curr - 1) || 1) : page.curr
                            }
                        } : {}));
                        break;
                    case 'modify':
                        //
                        console.log(obj)
                        layer.open({
                            type: 2
                            ,title: '添加新角色'
                            ,content: "{{url('/admins/purchase_order/show_goods')}}"
                            ,area: ['960px', '700px']
                            ,btn: ['确定', '取消']
                            ,yes: function(index, layero){
                                var iframeWindow = window['layui-layer-iframe'+ index]
                                    ,submit = layero.find('iframe').contents().find("#showSubmit");

                                //监听提交
                                iframeWindow.layui.form.on('submit(showSubmit)', function(data){
                                    var field = data.field; //获取提交的字段
                                    // $('#commodity').val(field.id_1)
                                    console.log(field)
                                    var tableObj = tableIns;
                                    var config = tableObj.config;
                                    var dataTemp = config.data;
                                    var page = config.page;
                                    // 得到tr的data-index
                                    var trElem = obj.tr.first();
                                    var index_1 = trElem.data('index');
                                    // 计算出在data中的index
                                    var dataIndex = index_1 + page.limit * (page.curr - 1);
                                    $skuArr = field.id_1.split("|");
                                    dataTemp[dataIndex].id=$skuArr[0];
                                    dataTemp[dataIndex].goods_sku=$skuArr[1];
                                    dataTemp[dataIndex].sku_name=$skuArr[2];
                                    dataTemp[dataIndex].goods_attr_name=$skuArr[3];
                                    dataTemp[dataIndex].goods_attr_value=$skuArr[4];
                                    dataTemp[dataIndex].goods_price=$skuArr[5];
                                    dataTemp[dataIndex].goods_num=0;
                                    dataTemp[dataIndex].goods_money=0;
                                    dataTemp[dataIndex].tax_rate=0;
                                    dataTemp[dataIndex].tax=0;
                                    dataTemp[dataIndex].money_tax=0;
                                    // 删除对应下标的数据
                                    // dataTemp.splice(dataIndex+1, 0,{id: 'new' + new Date().getTime()});

                                    // 新的页数
                                    var pageNew = Math.ceil(dataTemp.length / page.limit);

                                    // 重新接收reload返回的对象，这个很重要
                                    tableIns = table.reload(config.id, $.extend(true, {
                                        // 更新数据
                                        data: dataTemp
                                    }, config.page ? {
                                        // 如果删除了数据之后页数变了，打开前一页
                                        page: {
                                            curr: page.curr > pageNew ? ((page.curr - 1) || 1) : page.curr
                                        }
                                    } : {}));
                                    //提交 Ajax 成功后，静态更新表格中的数据
                                    //$.ajax({});
                                    // table.reload('LAY-user-back-role');
                                    layer.close(index); //关闭弹层
                                });

                                submit.trigger('click');
                            }
                        });
                        break;
                    default:
                        break;
                }
            });



            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                data.field.table = table.cache;
                $.ajax({
                    url:"{{url('admins/purchase_warehouse')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg=='0'){
                            layer.msg('添加成功！',{icon:1,time:2000},function () {
                                //调转
                                window.location.href = '/admins/purchase_warehouse';
                                return;
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
