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
            height: calc(60% - 3px);
            overflow: auto

        }
        .pane-bottom{
            /* background-color:pink; */
            bottom: 0;
            top: calc(60% + 3px);
            overflow: auto
        }
        .pane-trigger-con{
            width: 100%;
            background-color: red;
            position: absolute;
            z-index: 9;
            user-select: none;
            top: calc(60% - 3px);
            height: 6px;
            cursor: row-resize;
        }
        .layui-form-label{
            padding: 9px 0
        }
    </style>
    <div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">关键字：</label>
                    <div class="layui-input-block">
                        <div class="layui-inline" style="width:220px;">
                            <input class="layui-input" name="keywords" id="searchReload" placeholder="请输入订单编号/运单编号/收货人姓名电话详细地址"  autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态：</label>
                    <div class="layui-input-inline">
                        <select name="order_status" id="order_status">
                            <option value="1">拣货中</option>
                            <option value="2">已出库</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">日期：</label>
                    <div class="layui-input-inline">
                        <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间" autocomplete="off">
                    </div>
                    <div class="layui-input-inline">
                        <input class="layui-input" name="end_date" id="end_date" placeholder="结束时间" autocomplete="off">
                    </div>
                </div>

                <div class="layui-row demoTable">
                    <a class="layui-btn" data-type="reload" style="margin-left:600px;" id='search'>搜索</a>
                    &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>

            </div>



        </form>
    </div>

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
                                    <ul class="layui-tab-title">
                                        <li class="layui-this">商品信息</li>
                                        <li>安全设置</li>
                                    </ul>
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
    <script type="text/html" id="toolbar">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="getCheckData" id="export">导出拣货单</button>
        </div>
    </script>

    <script type="text/html" id="status">
        @{{# if(d.pick_status == 0){ }} <div style="color: #ff0000">未出库</div> @{{# if(d.pick_status == 1){ }} <div style="color: #008000">捡货中</div> @{{# }else{  }} <div style="color: #0000FF">已出库</div> @{{# }  }}
    </script>
@endsection
@section('js')
    <script>

        layui.use(['table','layer'], function(){
            var table = layui.table,
                layer = layui.layer,
                laydate = layui.laydate;
                $=layui.jquery;
            var conMove = false;
            $('.pane-trigger-con').mousedown(function(event){
                conMove = true
                $(document).mousemove(function  (event){
                    if (!conMove) return
                    // console.log(event)
                    // console.log($('.split-pane-warpper').height())
                    // console.log(event.pageY)
                    // console.log($('.pane-top').height())
                    // console.log($('.pane-bottom').height())
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
                ,url: "{{url('api/warehouse_pick')}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '产品数据表'
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,page: true //开启分页
                ,height: 'full-200'
                ,cols: [[ //表头
                    {type:'radio', fixed: 'left'}
                    ,{field: 'pick_code', title: '拣货单号', width: 150, fixed: 'left'}
                    ,{title: '状态', width: 80, fixed: 'left',templet:'#status'}
                    ,{field: 'id', title: 'ID', width:80, sort: true,}
                    ,{field: 'pick_name', title: '拣货人', width:100}
                    ,{field: 'pick_phone', title: '电话', width:120}
                    ,{ title: '仓库', width:120,templet:function (res) {
                        return res.warehouse.warehouse_name;
                    }}
                    ,{field: 'pick_text', title: '备注'}
                    ,{field: 'created_at', title: '创建时间', width: 160, sort: true}
                    ,{field: 'button', title: '操作', width: 200, fixed: 'right',
                        templet: function(row){
                            var status = '';
                            if(row.pick_status == 0){
                                status = '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="out">出库</a>';
                            }
                            return status;
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
                //search
                reload: function(){
                    var searchReload = $('#searchReload');

                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: searchReload.val(),
                            order_status: $("#order_status").val(),
                            start_date:$("#start_date").val(),
                            end_date:$("#end_date").val(),
                        }
                    }, 'data');
                },
            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            //头工具栏事件
            table.on('toolbar(list)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id); //获取选中行状态
                switch(obj.event){
                    case 'getCheckData':
                        var data = checkStatus.data;  //获取选中行数据
                        //layer.alert(JSON.stringify(data[0].id));
                        href = "{{url('admins/warehouse_pick/export/')}}/"+data[0].id;
                        location.href = href;
                        break;
                };
            });

            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(list)', function(obj){
                var data = obj.data;
                //console.log(data);
                table.render({
                    elem: '#table_list'
                    ,url: "{{url('api/warehouse_pick/order')}}/"+data.id //数据接口
                    ,cols: [[
                        {field: 'order_sn', title: '订单号', width: 150, fixed: 'left'}
                        ,{field: 'yunlu_sn', title: '运单号', width: 150, fixed: 'left'}
                        ,{field: 'id', title: 'ID', width:80, sort: true,}
                        ,{field: 'order_name', title: '收件人', width:100}
                        ,{field: 'order_phone', title: '电话', width:120}
                        ,{field: 'order_code', title: '邮编', width:80}
                        ,{field: 'order_province', title: '省', width:120}
                        ,{field: 'order_city', title: '市', width:120}
                        ,{field: 'order_county', title: '县', width:120}
                        ,{field: 'order_area', title: '区', width:120}
                        ,{field: 'order_address', title: '详细地址', width:220}
                        ,{field: 'ordered_at', title: '下单时间', width: 160}
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
                        area:['100%','100%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/purchase_order/')}}/"+data.id
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
                }else if(obj.event === 'out'){
                    $.ajax({
                        url:"{{url('admins/warehouse_pick/out/')}}/"+data.id,
                        type:'post',
                        data:{"_token":"{{csrf_token()}}"},
                        datatype:'json',
                        success:function (msg) {
                            if(msg=='0'){
                                layer.msg('审核成功！',{icon:1,time:2000},function () {
                                    window.location = window.location;
                                    layer.close(index);
                                });
                            }else{
                                layer.msg('审核失败！',{icon:2,time:2000});
                            }
                        },
                        error: function(XmlHttpRequest, textStatus, errorThrown){
                            layer.msg('error!',{icon:2,time:2000});
                        }
                    });

                }


            });



        });

    </script>
@endsection
