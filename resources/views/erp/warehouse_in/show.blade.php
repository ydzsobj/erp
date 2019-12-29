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
                                        <li class="layui-this">基本设置</li>
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


    <script type="text/html" id="warehouse_in_info_status">
        @{{# if(d.status == 0){ }} <div style="color: #ff0000">待入库</div> @{{# }else if(d.status == 1){  }} <div style="color: #008000">已入库</div> @{{# }  }}
    </script>
    <script type="text/html" id="warehouse_in_status">
        @{{# if(d.status == 0){ }} <div style="color: #ff0000">待入库</div> @{{# }else if(d.status == 1){  }} <div style="color: #008000">已入库</div>  @{{# }  }}
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
                    if (!conMove) return;

                    var pageY=event.pageY-92;
                    if (pageY < 100) pageY = 100;
                    if (pageY > $('.split-pane-warpper').height()-40) pageY = $('.split-pane-warpper').height()-40;
                    $('.pane-top').height(pageY);
                    $('.pane-bottom').css('top',pageY);
                    $('.pane-trigger-con').css('top',pageY);
                });
                $(document).mouseup(function  (event){
                    // console.log(event)
                    conMove = false
                })
            });

            //渲染实例
            table.render({
                elem: '#data_list'
                ,url: "{{url('api/warehouse_in')}}/{{$id}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '采购数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,height: 'full-400'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'status', title: '状态', width:80,templet:"#warehouse_in_status"}
                    ,{field: 'warehouse_in_code', title: '入库单号', width:150}
                    ,{field: 'created_at', title: '创建时间', width: 160}
                    ,{field: 'stored_at', title: '入库时间', width: 160}
                    ,{field: 'warehouse_text', title: '备注', width: 300}
                    ,{field: 'button', title: '操作', width: 220, fixed: 'right',
                        templet: function(row){
                            var status = '';
                            if(row.status == 0){
                               return '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="in">入库</a>';
                            }else if(row.status == 1){
                                return '';
                            }

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



            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(list)', function(obj){
                var data = obj.data;
                console.log(data);
                table.render({
                    elem: '#table_list'
                    ,url: "{{url('api/warehouse_in/goods')}}/"+data.id //数据接口
                    ,cols: [[
                        {field:'goods_sku', title: '商品编码', width:135, fixed: 'left'}
                        ,{field:'id', title: 'ID', width:80, sort: true}
                        ,{field:'goods_name', title: '商品名称', width:180}
                        ,{field:'goods_color', title: '颜色', width:100}
                        ,{field:'goods_size', title: '尺寸', width:100}
                        ,{field:'goods_num', title: '商品总数',width:100}
                        ,{field:'order_num', title: '订单数量',width:100}
                        ,{field:'plan_num', title: '备货数量',width:100}
                        ,{field:'goods_money', title: '总价',  width:100}
                        ,{title: '状态',templet:"#warehouse_in_info_status"}

                    ]]
                    ,id: 'testReload'
                });

                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });

            //监听工具条
            table.on('tool(list)', function(obj){
                var data = obj.data;

                if(obj.event === 'in'){
                    layer.confirm('真的确定入库？', function(index){
                        $.ajax({
                            url:"{{url('admins/warehouse_in/in/')}}/"+data.id,
                            type:'post',
                            data:{"_token":"{{csrf_token()}}"},
                            datatype:'json',
                            success:function (msg) {
                                if(msg=='0'){
                                    layer.msg('提交成功！',{icon:1,time:2000},function () {
                                        window.location = window.location;
                                        layer.close(index);
                                    });
                                }else{
                                    layer.msg(msg.msg,{icon:2,time:2000});
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
