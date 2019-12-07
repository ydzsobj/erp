@extends('erp.father.father')
@section('content')
    <style>
        html,body{
            height: 100%;
        }
        .split-pane-warpper{
            width: 100%;
            height: 100%;
            position: relative;

        }
        .pane{
            width: 100%;
            position:absolute;

        }
        .pane-top{
            /* background-color: palevioletred; */
            height: calc(50% - 3px);
            overflow: auto

        }
        .pane-bottom{
            /* background-color:pink; */
            bottom: 0;
            top: calc(50% + 3px);
            overflow: auto
        }
        .pane-trigger-con{
            width: 100%;
            background-color: red;
            position: absolute;
            z-index: 9;
            user-select: none;
            top: calc(50% - 3px);
            height: 6px;
            cursor: row-resize;
        }
        .layui-form-label{
            padding: 9px 0
        }
    </style>


<!--筛选开始-->
<div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">请输入</label>
                    <div class="layui-input-block">
                        <div class="layui-inline" style="width:265px;">
                            <input class="layui-input" name="keywords" id="keywords" placeholder="产品名称/SKU编号"  autocomplete="off">
                        </div>
                    </div>
                </div>

                    <div class="layui-inline">
                        <a class="layui-btn" data-type="reload"  id='search'>查询</a>
                        &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
            </div>

        </form>
</div>

<!--表格面板-->
    <div style="width: 100%;height: calc(100% - 92px);">
        <div class="split-pane-warpper">
            <div class="pane pane-top" >
                <div class="layui-card-body">
                    <table id="data_list" lay-filter="data_list"></table>
                </div>
            </div>
            <div class="pane pane-trigger-con"></div>
            <div class="pane pane-bottom" >
                <!-- <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> -->
                <div class="layui-fluid">
                    <div class="layui-row layui-col-space15">
                        <div class="layui-col-md12">
                            <div class="layui-card">
                                <div class="layui-tab layui-tab-card">
                                    <form class="layui-form" action="">
                                        <ul class="layui-tab-title">
                                            <li class="layui-this"><b id="target_product_info"></b> 库存明细</li>
                                            <li>

                                                <label>
                                                    业务时间 :
                                                </label>
                                                <div class="layui-inline" style="width:150px;">
                                                    <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间">
                                                </div>-
                                                <div class="layui-inline" style="width:150px;">
                                                    <input class="layui-input" name="end_date" id="end_date" placeholder="结束时间">
                                                </div>
                                            </li>
                                            <li>
                                                    <label>
                                                        SKU编码 :
                                                    </label>
                                                    <div class="layui-inline" style="width:150px;">
                                                        <input class="layui-input" name="goods_sku" id="goods_sku" placeholder="请输入sku">
                                                    </div>
                                            </li>
                                            <li>
                                                <a class="layui-btn layui-btn-sm" data-type="sub_reload"  id='sub_search'>查询</a>
                                            </li>
                                            <li>
                                                <button type="reset" class="layui-btn layui-btn-sm layui-btn-primary">重置</button>
                                            </li>

                                        </ul>
                                    </form>
                                    <div class="layui-tab-content">
                                        <div class="layui-tab-item layui-show">
                                            <table class="layui-hide" id="table_list" lay-filter="table_list"></table>
                                        </div>
                                        <div class="layui-tab-item">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>

        layui.use(['table', 'upload','layer', 'laydate'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;
                var laydate = layui.laydate;
                var upload = layui.upload;
            var conMove = false;
            $('.pane-trigger-con').mousedown(function(event){
                conMove = true
                $(document).mousemove(function  (event){
                    if (!conMove) return
                    var pageY=event.pageY-92
                    if (pageY < 100) pageY = 100
                    if (pageY > $('.split-pane-warpper').height()-40) pageY = $('.split-pane-warpper').height()-40
                    $('.pane-top').height(pageY)
                    $('.pane-bottom').css('top',pageY)
                    $('.pane-trigger-con').css('top',pageY)
                })
                $(document).mouseup(function  (event){
                    // console.log(event)
                    conMove = false
                })
            });

             //点击搜索
            $('#search').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
                console.log('11');
                //执行重载

            });

            $('#sub_search').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';

            });

            var active = {
                reload: function(){

                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: $("#keywords").val()
                        }
                    }, 'data');

                    table.reload('testReload', {
                       data:[]
                    });
                },

                sub_reload: function(){

                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            start_date: $("#start_date").val(),
                            end_date: $("#end_date").val(),
                            goods_sku:$("#goods_sku").val(),
                        }
                    }, 'data');

                }

            };

            laydate.render({
                elem: '#start_date'
                ,type: 'datetime'
            });

            laydate.render({
                elem: '#end_date'
                ,type: 'datetime'
            });

            //渲染实例
            table.render({
                elem: '#data_list'
                ,url: "{{url('api/inventory')}}" //数据接口
                ,where: {
                    warehouse_id:  {{ $warehouse_id }}
                }
                ,id: 'listReload'
                ,toolbar: '#toolbarDemo'
                ,parseData: function(res){ //res 即为原始返回的数据
                        return {
                            "code": res.code, //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data.data //解析数据列表
                        };
                    }
                ,defaultToolbar: []
                ,title: '库存数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {type: 'checkbox', width:50}
                    ,{field: 'id', title: 'ID', width:80, sort: true}
                    ,{title: '商品名称',width:220, templet:function (res) {
                            return res.sku.sku_name;
                    }}
                    ,{field: 'goods_sku', title: 'SKU编码', width:160}

                    ,{title: '属性值',templet:function (res) {
                            return res.sku.sku_attr_value_names;
                    }}

                    ,{field: 'stock_num', title: '库存', style:'color: green;'}
                    ,{field: 'afloat_num', title: '在途',  style:'color: blue;'}
                    ,{field: 'in_num', title: '入库数量'}
                    ,{field: 'out_num', title: '出库数量'}
                    ,{field: 'goods_position', title: '库位'}
                    ,{field: 'goods_text', title: '商品备注'}


                ]]
            });

            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(data_list)', function(obj){
                var data = obj.data;

                table.reload('testReload',{
                    where: {
                        goods_sku: data.goods_sku
                    }
                })
                // console.log(data);
                // $("#target_product_info").text('[' + data.sku.sku_name + ']');
                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });

            table.render({
                elem: '#table_list'
                ,url: "{{ url('api/inventory_info')}}"//数据接口
                ,where: {
                    warehouse_id: {{ $warehouse_id }}
                }
                ,page: true //开启分页
                ,parseData: function(res){ //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data.data //解析数据列表
                    };
                }
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[
                    {field:'created_at', width:200, title: '业务时间', sort:true}
                    ,{field:'in_num', width:120, title: '入库数量'}
                    ,{field:'out_num', width:120, title: '出库数量'}
                    ,{field:'goods_sku', title: 'SKU编码'}
                    ,{title: '产品名称', wifth:260,  templet: function(res){
                        return res.sku.sku_name;
                    }}
                    ,{title: '属性值',  templet: function(res){
                        return res.sku.sku_attr_value_names;
                    }}
                    ,{field:'stock_type', title: '业务类型'}
                    ,{field:'user_id', title: '操作人', templet: function(res){
                        return res.admin.admin_name;
                    }}
                ]]
                ,id: 'testReload'
            });

            //监听单元格编辑
            table.on('edit(data_list)', function(obj){
                var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
                console.log(obj);
                $.ajax({
                    type:'post',
                    url:"/admins/inventory/" + obj.data.id + '/goods_position',
                    data:{_token:"{{ csrf_token() }}", goods_position: obj.value},
                    success:function(msg){
                        if(msg=='0'){
                            layer.msg('设置成功！',{icon:1,time:2000},function () {
                                window.location = window.location;
                                layer.close(index);
                            });
                        }else{
                            layer.msg('设置失败！',{icon:2,time:2000});
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
            });



            //监听工具条
            table.on('tool(data_list)', function(obj){
                var data = obj.data;

                if(obj.event === 'detail'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'基本信息',
                        area:['350px','420px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/supplier/')}}/"+data.id
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){



                    });
                } else if(obj.event === 'edit'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'编辑信息',
                        area:['800px','600px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/supplier/')}}/"+data.id+"/edit"
                    });
                    //layer.alert('编辑行：<br>'+ JSON.stringify(data))
                }else if(obj.event === 'check'){


                }
            });

            //监听头部工具条
            table.on('toolbar(data_list)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(obj);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

                var checkStatus = table.checkStatus(obj.config.id);

                console.log(checkStatus.data);

                if(layEvent === 'import_order'){ //
                    //do somehing
                    console.log('click import store');
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'待入库列表',
                        area: ['960px', '600px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{ route('inventory.yn_in_create') }}?warehouse_id={{ $yn_virtual_warehouse_id }}",
                        end :function(){
                            do_reload();

                        }
                    });
                }else if(layEvent == 'batch_audit'){
                    //批量审核
                    if(checkStatus.data.length == 0){
                        layer.msg('请先选择订单');
                        return false;
                    }

                    var selected_rows = checkStatus.data;
                    var selected_ids = [];
                    for(var i=0;i<selected_rows.length;i++){
                        selected_ids.push(selected_rows[i].id);
                    }
                    console.log(selected_ids);

                    layer.confirm('确定要审核通过吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: "{{ route('orders.batch_audit') }}",
                            data:{ _token: "{{ csrf_token() }}" ,order_ids: selected_ids },
                            dataType:"json",
                            success:function(msg){
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                    if(msg.success){
                                    table.reload('demo');
                                    }
                            },
                            error: function(data){
                                layer.msg('请求接口失败',{icon:2,time:2000});
                            }

                        })
                    });

                }
            });

            function do_reload(){
            // var demoReload = $('#demoReload');
                table.reload('listReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {

                    }
                }, 'data');
        }


            // //执行实例
            var uploadInst = upload.render({
                elem: '#import' //绑定元素
                ,url: '/admins/uploader/pic_upload' //上传接口
                ,accept: 'file' //所有文件
                ,exts: 'xls|xlsx' //后缀
                ,data: {_token:"{{ csrf_token() }}"}
                ,done: function(res){
                    //上传完毕回调
                    // console.log(res, $(".country_id:checked"));
                    if(res.code == 0){
                        $.ajax({
                            type:'POST',
                            url: "{{route('inventory.import')}}",
                            data:{
                                path:res.path,
                                _token:"{{ csrf_token()}}",
                                warehouse_id:{{ $warehouse_id }}
                            },
                            success:function(msg){
                                console.log(msg);
                                layer.open({
                                    title: '提示',
                                    content: msg,
                                    yes:function(index, layero){
                                        table.reload('listReload', {
                                            where: { //设定异步数据接口的额外参数，任意设
                                                aaaaaa: 'xxx'
                                                ,bbb: 'yyy'
                                                //…
                                            }
                                            ,page: {
                                                curr: 1 //重新从第 1 页开始
                                            }
                                            }); //只重载数据

                                        layer.closeAll();

                                        // window.location.reload();
                                    }
                                });
                            },
                            error: function(data){
                                var errors = JSON.parse(data.responseText).errors;
                                var msg = '';
                                for(var a in errors){
                                    msg += errors[a][0]+'<br />';
                                }
                                    layer.msg(msg,{icon:2,time:2000});
                            }
                        })
                    }else{
                        layer.msg('上传失败，请重新上传');
                    }
                }
                ,error: function(){
                    //请求异常回调
                }
            });



        });

    </script>
@endsection

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="import_order" >入库</button>
      <button class="layui-btn layui-btn-sm" lay-event="" >出库</button>
    </div>
  </script>
