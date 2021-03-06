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

<div class="layui-row" style="margin-top:10px;">
    <form class="layui-form" action="" id ="fm_import">
        <div class="layui-form-item">
            <label class="layui-form-label">选择文件</label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="import">导入数据</button>
            </div>

            <label class="layui-form-label">
                <a href="{{ asset('templates/入库模板.xlsx') }}">
                    <span style="color:red;">下载模板</span>
                </a>
            </label>
        </div>
    </form>
</div>

<!--筛选开始-->
<div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <span style="margin-left:20px;">
                        导入时间 :
                    </span>
                    <div class="layui-inline" style="width:150px;">
                        <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间">
                    </div>-
                    <div class="layui-inline" style="width:150px;">
                        <input class="layui-input" name="end_date" id="end_date" placeholder="结束时间">
                    </div>
                </div>

                    <div class="layui-inline">
                            <a class="layui-btn" data-type="reload"  id='search'>查询</a>
                            &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
            </div>
        </form>
</div>

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
                                                <li class="layui-this"><b id="target_product_info"></b> 导入明细</li>

                                                <li>
                                                    <label>
                                                        订单编码 :
                                                    </label>
                                                    <div class="layui-inline" style="width:180px;">
                                                        <input class="layui-input" name="import_order_sn" id="import_order_sn" placeholder="请输入订单sn">
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
                $=layui.jquery
                , tablePlug = layui.tablePlug //表格插件
                , testTablePlug = layui.testTablePlug ;// 测试js模块
                var upload = layui.upload;
                var laydate = layui.laydate;

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

            //渲染实例
            table.render({
                elem: '#data_list'
                ,url: "{{url('api/inventory_import_logs')}}" //数据接口
                ,where: {
                    warehouse_id:  {{ $warehouse_id }}
                }
                ,id: 'listReload'
                // ,toolbar: '#toolbarDemo'
                ,parseData: function(res){ //res 即为原始返回的数据
                        return {
                            "code": res.code, //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data.data //解析数据列表
                        };
                    }
                ,primaryKey:'id'
                , checkDisabled: {
                    enabled: true,
                    data: [42]
                    }
                ,defaultToolbar: []
                ,title: '库存数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'created_at', title: '导入时间'}
                    ,{field: 'import_nums', title: '单数'}
                    ,{field: 'admin', title: '操作人', templet:function(row){
                        return row.admin.admin_name;
                    }}

                ]]
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
                            start_date: $("#start_date").val(),
                            end_date: $("#end_date").val(),

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
                            import_order_sn: $("#import_order_sn").val(),
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


            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(data_list)', function(obj){
                var data = obj.data;
                // console.log(data);
                table.reload('testReload',{
                    where: {
                        targetable_type: 'inventory_import_logs',
                        targetable_id: data.id,
                    }
                })
                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });

            table.render({
                elem: '#table_list'
                ,url: "{{ url('api/inventory_info')}}"//数据接口
                ,where: {
                    warehouse_id:  {{ $warehouse_id }},
                    import_order_sn: 0
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
                    {field:'created_at', width:200, title: '时间', sort:true}
                    ,{field:'in_num', width:120, title: '入库数量'}

                    ,{field:'goods_sku', title: 'SKU编码'}
                    ,{title: '产品名称', wifth:260,  templet: function(res){
                        return res.sku.sku_name;
                    }}
                    ,{field:'import_order_sn', title: '订单号'}
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
                    url:"/admins/inventory/" + obj.data.id + '/update_fields',
                    data:{_token:"{{ csrf_token() }}", update_field: obj.field, update_field_value: obj.value},
                    success:function(msg){
                        if(msg.success){
                            layer.msg('设置成功！',{icon:1,time:2000},function (index) {
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

                        $.ajax({
                            url:"{{url('admins/supplier/')}}/"+data.id,
                            type:'delete',
                            data:{"_token":"{{csrf_token()}}"},
                            datatype:'json',
                            success:function (msg) {
                                if(msg=='0'){
                                    layer.msg('删除成功！',{icon:1,time:2000},function () {
                                        obj.del();
                                        layer.close(index);
                                    });
                                }else{
                                    layer.msg('删除失败！',{icon:2,time:2000});
                                }
                            },
                            error: function(XmlHttpRequest, textStatus, errorThrown){
                                layer.msg('error!',{icon:2,time:2000});
                            }
                        });


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
                        title:'入库',
                        type: 1,
                        area:['600px','300px'],
                        content: $("#fm_import")
                    })
                }else if(layEvent == 'batch_out'){
                    //批量出库
                    if(checkStatus.data.length == 0){
                        layer.msg('请先勾选数据');
                        return false;
                    }

                    var selected_rows = checkStatus.data;
                    var selected_ids = [];
                    for(var i=0;i<selected_rows.length;i++){
                        selected_ids.push(selected_rows[i].id);
                    }
                    console.log(selected_ids);

                    layer.confirm('确定要出库吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: "{{ route('inventory.yn_virtual_out') }}",
                            data:{ _token: "{{ csrf_token() }}" ,out_data: selected_rows },
                            dataType:"json",
                            success:function(msg){
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                    if(msg.success){
                                        table.reload('listReload');
                                        table.reload('testReload');
                                    }
                            },
                            error: function(data){
                                layer.msg('请求接口失败',{icon:2,time:2000});
                            }

                        })
                    });

                }
            });

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

                                        layer.close(index);
                                        table.reload('listReload');
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

{{-- <script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="import_order" >入库</button>
      <button class="layui-btn layui-btn-sm" lay-event="batch_out" >出库</button>
    </div>
  </script> --}}

