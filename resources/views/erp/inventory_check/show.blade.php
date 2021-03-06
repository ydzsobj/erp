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
            height: calc(40% - 3px);
            overflow: auto

        }
        .pane-bottom{
            /* background-color:pink; */
            bottom: 0;
            top: calc(40% + 3px);
            overflow: auto
        }
        .pane-trigger-con{
            width: 100%;
            background-color: red;
            position: absolute;
            z-index: 9;
            user-select: none;
            top: calc(40% - 3px);
            height: 6px;
            cursor: row-resize;
        }
        .layui-form-label{
            padding: 9px 0
        }
    </style>

    <div style="width: 100%;height: calc(100% - 92px);">
        <div class="split-pane-warpper">
            <div class="pane pane-top" >
                <div class="layui-card-body">
                    <table id="data_list" lay-filter="list"></table>
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
                                            <li class="layui-this"><b id="target_product_info"></b> 盘点信息</li>
                                            <li>
                                                <label>
                                                    SKU编码 :
                                                </label>
                                                <div class="layui-inline" style="width:150px;">
                                                    <input class="layui-input" name="goods_sku" id="goods_sku" placeholder="请输入sku">
                                                </div>
                                            </li>
                                            <li>
                                                状态：
                                                <div class="layui-inline" style="width:110px;">
                                                    <select name="out_status" id="select_status" >
                                                        <option value="0">未更新</option>
                                                        <option value="1">已更新</option>
                                                    </select>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="bar">
        <div class="layui-btn-container demo">
            <button class="layui-btn" data-type="getCheckData">批量更新库存</button>
        </div>
    </script>
    <script type="text/html" id="toolbar">
        <div class="layui-btn-container demoTable">
            <button class="layui-btn layui-btn-sm" data-type="add" onclick="create_show('导入盘点单','{{url("admins/inventory_check/import/1")}}',2,'50%','50%');">导入盘点单</button>
        </div>
    </script>

    <script type="text/html" id="inventory_check_status">
        @{{# if(d.inventory_check_status == 0){ }} <div style="color: #ff0000">待入库</div> @{{# }else if(d.inventory_check_status == 1){  }} <div style="color: #008000">验货中</div>  @{{# }else{  }} <div>已退货</div> @{{# }  }}
    </script>
    <script type="text/html" id="inventory_check_info_status">
        @{{# if(d.inventory_check_info_status == 0){ }} <div style="color: #ff0000">未更新</div> @{{# }else if(d.inventory_check_info_status == 1){  }} <div style="color: #008000">已更新</div> @{{# }else{  }} <div>已完成</div> @{{# }  }}
    </script>
@endsection
@section('js')
    <script>

        layui.use(['table','layer'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;
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
                ,url: "{{url('api/inventory_check')}}/{{$id}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '盘点数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,height: '280px'
                ,cols: [[ //表头
                    {type: 'radio', fixed: 'left'}
                    ,{field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'inventory_check_status', title: '状态', width:80,templet:"#inventory_check_status"}
                    ,{field: 'inventory_check_code', title: '盘点单号', width:150}
                    ,{field: 'created_at', title: '创建时间', width: 160}
                    ,{field: 'inventory_check_text', title: '备注', width: 300}
                    ,{field: 'button', title: '操作', width: 220, fixed: 'right',
                        templet: function(row){
                            var status = '';
                            if(row.inventory_check_status == 0){
                                status = '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="check">审核</a>';
                            }
                            return status + '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="look">查看</a>'+
                                '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';
                        }
                    }
                ]]
            });


            create_show = function create_show(title,url,type,w,h) {
                if(layui.device().android||layui.device().ios){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:type,
                        title:title,
                        area:['375px','667px'],
                        fixed:false,
                        maxmin:true,
                        content:url
                    });
                }else {
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:type,
                        title:title,
                        area:[w,h],
                        fixed:false,
                        maxmin:true,
                        content:url
                    });
                }
            };


            var active = {
                reload: function(){
                    var searchReload = $('#searchReload');
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                    }, 'data');
                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: searchReload.val()
                        }
                    }, 'data');
                },
                getCheckData:function(){
                    layer.confirm('是否确定批量更新库存？', function(index) {
                        var checkStatus = table.checkStatus('testReload');
                        if(checkStatus.data.length==0){
                            parent.layer.msg('请先选择要生成的数据行！', {icon: 2});
                            return ;
                        }
                        var codeId= "";
                        for(var i=0;i<checkStatus.data.length;i++){
                            codeId += checkStatus.data[i].id+",";
                        }
                        parent.layer.msg('更新中...', {icon: 16,shade: 0.3,time:2000});
                        layer.close(index);
                        $.ajax({
                            type:"POST",
                            url: "{{url('admins/inventory_check/all')}}",
                            data:{"ids":codeId,"_token":"{{csrf_token()}}","warehouse_id":"{{$id}}"},
                            success:function (data) {
                                layer.closeAll('loading');
                                if(data==0){
                                    parent.layer.msg('生成成功！', {icon: 1,time:2000,shade:0.2},function () {
                                        location.reload(true);
                                    });
                                }else{
                                    parent.layer.msg('生成失败！', {icon: 2,time:3000,shade:0.2});
                                }
                            },
                            end: function () {
                                var data1 = table.cache["list"];
                                t.where = data1.field;
                                //重新加载数据表格
                                table.reload('listReload',t);
                            }
                        })
                    });

                }


            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            $('body').on('click','.demo .layui-btn', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });


            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(list)', function(obj){
                var data = obj.data;

                table.render({
                    elem: '#table_list'
                    ,url: "{{url('api/inventory_check/goods')}}/"+data.id //数据接口
                    ,toolbar: '#bar'
                    ,defaultToolbar: ['filter', 'exports', 'print']
                    ,title: '盘点数据表'
                    ,page: true //开启分页
                    ,count: 10000
                    ,limit: 100
                    ,limits: [100,300,500,1000,2000,5000,10000]
                    ,height: '500px'
                    ,cols: [[
                        {type: 'checkbox', fixed: 'left'}
                        ,{field:'goods_sku', title: '商品编码', width:135, fixed: 'left'}
                        ,{field:'id', title: 'ID', width:100, sort: true}
                        ,{field:'goods_name', title: '商品名称', width:180}
                        ,{field:'goods_color', title: '颜色', width:100}
                        ,{field:'goods_size', title: '尺码', width:100}
                        ,{field:'goods_num', title: '盘点数量',width:120}
                        ,{field: 'inventory_check_info_status', title: '状态', width: 100, templet:'#inventory_check_info_status'}
                        ,{field: 'button', title: '操作', width: 180, fixed: 'right',
                            templet: function(row){
                                var status = '';
                                if(row.inventory_check_info_status == 0){
                                    status = '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="change">更新库存</a>';
                                }
                                return status;
                            }
                        }
                    ]]
                    ,id: 'testReload'
                });

                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });

            //监听工具条
            table.on('tool(list)', function(obj){
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
                            url:"{{url('admins/inventory_check/')}}/"+data.id,
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
                }else if(obj.event === 'check'){
                    $.ajax({
                        url:"{{url('admins/inventory_check/check/')}}/"+data.id,
                        type:'post',
                        data:{"_token":"{{csrf_token()}}"},
                        datatype:'json',
                        success:function (msg) {
                            if(msg=='0'){
                                layer.msg('操作成功！',{icon:1,time:2000},function () {
                                    window.location = window.location;
                                    layer.close(index);
                                });
                            }else{
                                layer.msg('操作失败！',{icon:2,time:2000});
                            }
                        },
                        error: function(XmlHttpRequest, textStatus, errorThrown){
                            layer.msg('error!',{icon:2,time:2000});
                        }
                    });

                }


            });


            //监听工具条
            table.on('tool(table_list)', function(obj){
                layer.confirm('是否确定更新入库！？', function(index) {
                    var data = obj.data;
                    layer.close(index);
                    if(obj.event === 'change'){
                        $.ajax({
                            url:"{{url('admins/inventory_check/change/')}}/"+data.id,
                            type:'post',
                            data:{"_token":"{{csrf_token()}}","warehouse_id":"{{$id}}"},
                            datatype:'json',
                            success:function (msg) {
                                if(msg=='0'){
                                    layer.msg('操作成功！',{icon:1,time:1000},function () {
                                        window.location = window.location;
                                        layer.close(index);
                                    });
                                }else{
                                    layer.msg('操作失败！',{icon:2,time:2000});
                                }
                            },
                            error: function(XmlHttpRequest, textStatus, errorThrown){
                                layer.msg('error!',{icon:2,time:2000});
                            }
                        });

                    }


                });


            });



        });

    </script>
@endsection
