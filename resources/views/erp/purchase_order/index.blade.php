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
            height: calc(70% - 3px);
            overflow: auto

        }
        .pane-bottom{
            /* background-color:pink; */
            bottom: 0;
            top: calc(70% + 3px);
            overflow: auto
        }
        .pane-trigger-con{
            width: 100%;
            background-color: red;
            position: absolute;
            z-index: 9;
            user-select: none;
            top: calc(70% - 3px);
            height: 6px;
            cursor: row-resize;
        }
        .layui-form-label{
            padding: 9px 0
        }
    </style>

    <div class="layui-form" style="padding: 4px 0;height:40px;">
        <div class="layui-inline">
            <div class="">
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围：</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test-laydate-start"
                               placeholder="开始日期">
                    </div>
                    <!-- <div class="layui-form-mid"> -->
                    -
                    <!-- </div> -->
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="test-laydate-end"
                               placeholder="结束日期">
                    </div>
                </div>
                <!-- <span style="color: red">时间不选择默认为近10天</span> -->
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围：</label>
                    <div class="layui-inline">
                        <input class="layui-input" name="id" id="demoReload" autocomplete="off">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">选择框：</label>
                    <div class="layui-input-inline">
                        <select name="quiz">
                            <option value="">请选择问题</option>
                            <option value="0">北京</option>
                            <option value="1">上海</option>
                            <option value="2">广州</option>
                            <option value="3">深圳</option>
                            <option value="4">杭州</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">商品：</label>
                    <div class="layui-inline">
                        <input class="layui-input" name="id" id="commodity" autocomplete="off">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">日期范围：</label>
                    <div class="layui-inline">
                        <input class="layui-input" name="id" id="demoReload" autocomplete="off">
                    </div>
                    <button class="layui-btn layui-btn-sm" data-type="reload">搜索</button>
                </div>
            </div>
        </div>
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
            <button class="layui-btn layui-btn-sm" data-type="add" onclick="create_show('添加采购单','{{url("admins/purchase_order/create")}}',2,'100%','100%');">添加采购单</button>
        </div>
    </script>

    <script type="text/html" id="purchase_order_status">
        @{{# if(d.purchase_order_status == 0){ }} <div style="color: #ff0000">未审核</div> @{{# }else if(d.purchase_order_status == 1){  }} <div style="color: #008000">已审核</div>
        @{{# }else if(d.purchase_order_status == 2){  }} <div style="color: #0000FF">入库单</div> @{{# }else if(d.purchase_order_status == 3){  }} <div style="color: #fcd000">已出货</div>
        @{{# }else{  }} <div style="color: #000">已完成</div>@{{# }  }}
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
                ,url: "{{url('api/purchase_order')}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '采购数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,height: 'full-300'
                ,cols: [[ //表头
                    {field: 'logistics_code', fixed: 'left',title: '物流单号', width:150,edit:true}
                    ,{field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'purchase_order_status', title: '状态', width:70,templet:"#purchase_order_status"}
                    ,{field: 'purchase_order_code', title: '采购单号', width:130}
                    ,{field: 'expect_out_at', title: '预计出货时间', width:160}
                    ,{field: 'out_at', title: '实际出货日期', width:160}
                    ,{field: 'expect_deliver_at', title: '预计到货日期', width:160}
                    ,{field: 'deliver_at', title: '实际到货日期', width:160}
                    ,{field: 'created_at', title: '创建时间', width: 160}
                    ,{field: 'checked_at', title: '审核时间', width: 160}
                    ,{field: 'purchase_text', title: '备注', width: 160}
                    ,{field: 'button', title: '操作', width: 200, fixed: 'right',
                        templet: function(row){
                            var status = '';
                            if(row.purchase_order_status == 0){
                                status = '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="check">审核</a>';
                            }else if(row.purchase_order_status == 1){
                                status = '<a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="add">生成入库单</a>';
                            }
                            else if(row.purchase_order_status == 2){
                                status = '<a class="layui-btn layui-btn-xs layui-btn" lay-event="time">已出货</a>'
                                + '<a class="layui-btn layui-btn-xs layui-btn" lay-event="note">记录</a>';
                            }
                        return status + '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="look">查看</a>'+
                            '<a class="layui-btn layui-btn-xs" lay-event="edit">维护</a>'+
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
                }
            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });


            //监听单元格编辑
            table.on('edit(list)', function(obj){
                var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
                console.log(obj);
                $.ajax({
                    type:'post',
                    url:"/admins/purchase_order/" + obj.data.id + '/code',
                    data:{_token:"{{ csrf_token() }}", code: obj.value},
                    success:function(msg){
                        if(msg=='0'){
                            layer.msg('设置成功！',{icon:1,time:1000},function () {
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


            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(list)', function(obj){
                var data = obj.data;
                //console.log(data);
                table.render({
                    elem: '#table_list'
                    ,url: "{{url('api/purchase_order/goods')}}/"+data.id //数据接口
                    ,cols: [[
                        {field:'id', title: 'ID', width:80, sort: true}
                        ,{field:'goods_id', title: 'SKU ID', width:100, sort: true}
                        ,{field:'goods_name', title: '商品名称', width:180}
                        ,{field:'goods_attr_name', title: '属性名', width:100}
                        ,{field:'goods_attr_value', title: '属性值', width:100}
                        ,{field:'goods_num', title: '商品总数',width:120}
                        ,{field:'order_num', title: '订单数量',width:120}
                        ,{field:'plan_num', title: '备货数量',width:120}
                        ,{field:'goods_money', title: '总价',  width:120}
                        ,{field:'goods_sku', title: '商品编码', width:135, fixed: 'right'}
                    ]]
                    ,id: 'testReload'
                });

                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });


            //监听工具条
            table.on('tool(list)', function(obj){
                var data = obj.data;

                if(obj.event === 'look'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'基本信息',
                        area:['600px','600px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/purchase_order/')}}/"+data.id+"/look"
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                }else if(obj.event === 'add'){
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
                }else if(obj.event === 'check'){
                        $.ajax({
                            url:"{{url('admins/purchase_order/check/')}}/"+data.id,
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

                }else if(obj.event === 'time'){
                    $.ajax({
                        url:"{{url('admins/purchase_order/time/')}}/"+data.id,
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


                }else if(obj.event === 'note'){
                    layer.prompt({ title: '添加物流轨迹，并确认', formType: 2 }, function (text, index) {
                        $.ajax({
                            url:"{{url('admins/purchase_order/note/')}}/"+data.id,
                            type:'post',
                            data:{"_token":"{{csrf_token()}}","text":text},
                            datatype:'json',
                            success:function (msg) {
                                if(msg=='0'){
                                    layer.close(index);
                                    layer.msg('添加完毕！已获取您输入的物流轨迹，<br>您最后写下了：' + text);
                                }else{
                                    layer.msg('操作失败！',{icon:2,time:2000});
                                }
                            },
                            error: function(XmlHttpRequest, textStatus, errorThrown){
                                layer.msg('error!',{icon:2,time:2000});
                            }
                        });

                    });

                }



            });



        });

    </script>
@endsection
