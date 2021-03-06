@extends('erp.father.father')
@section('content')

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

        </div>
    </div>



    <script type="text/html" id="purchase_order_status">
        @{{# if(d.problem_status == 0){ }} <div style="color: #ff0000">待处理</div> @{{# }else if(d.problem_status == 1){  }} <div style="color: #008000">处理中</div> @{{# }else if(d.problem_status == 2){  }} <div style="color: #0000FF">已处理</div> @{{# }  }}
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
                ,url: "{{url('api/problem')}}/{{$id}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '采购数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,height: 'full-100'
                ,cols: [[ //表头
                    {type: 'radio', fixed: 'left'}
                    ,{field: 'id', title: 'ID', width:80, sort: true}
                    ,{title: '状态', width:80,templet:"#purchase_order_status"}
                    ,{field: 'relate_id', title: '关联ID', width:80}
                    ,{title: '商品名称', width: 180,templet:function (res) {
                            return res.purchase_warehouse_info.goods_name;
                        }}
                    ,{title: '商品sku', width: 120,templet:function (res) {
                            return res.purchase_warehouse_info.goods_sku;
                        }}
                    ,{title: '采购数量', width: 100,templet:function (res) {
                            return res.purchase_warehouse_info.goods_num;
                        }}
                    ,{title: '入库数量', width: 100,templet:function (res) {
                            return res.purchase_warehouse_info.real_num;
                        }}
                    ,{title: '差额数量', width: 100,templet:function (res) {
                            return res.purchase_warehouse_info.balance;
                        }}
                    ,{title: '订单数量', width: 100,templet:function (res) {
                            return res.purchase_warehouse_info.order_num;
                        }}
                    ,{title: '备货数量', width: 100,templet:function (res) {
                            return res.purchase_warehouse_info.plan_num;
                        }}
                    ,{field: 'created_at', title: '创建时间', width: 160}
                    ,{field: 'problem_text', title: '备注', width: 300}
                    ,{field: 'button', title: '操作', width: 220, fixed: 'right',
                        templet: function(row){
                            var status = '';
                            if(row.problem_status == 0){
                                status = '<a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="check">处理</a>';
                            }else if(row.problem_status == 1){
                                status = '<a class="layui-btn layui-btn-xs layui-btn" lay-event="in">入库</a>';
                            }
                            return status + '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>'+
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

            //头工具栏事件
            table.on('toolbar(list)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id); //获取选中行状态
                switch(obj.event){
                    case 'getCheckData':
                        var data = checkStatus.data;  //获取选中行数据
                        layer.alert(JSON.stringify(data));
                        break;
                };
            });

            //监听行单击事件（单击事件为：rowDouble）
            table.on('row(list)', function(obj){
                var data = obj.data;
                console.log(data);
                table.render({
                    elem: '#table_list'
                    ,url: "{{url('api/purchase_warehouse/goods')}}/"+data.relate_id //数据接口
                    ,cols: [[
                        {field:'goods_sku', title: '商品编码', width:135, fixed: 'left'}
                        ,{field:'id', title: 'ID', width:80, sort: true}
                        ,{field:'goods_name', title: '商品名称', width:180}
                        ,{field:'goods_attr_name', title: '属性名', width:100}
                        ,{field:'goods_attr_value', title: '属性值', width:100}
                        ,{field:'goods_num', title: '商品总数',width:100}
                        ,{field:'order_num', title: '订单数量',width:100}
                        ,{field:'plan_num', title: '备货数量',width:100}
                        ,{field:'goods_money', title: '总价',  width:100}
                        ,{title: '状态',templet:"#purchase_warehouse_info_status"}
                        ,{field: 'button', title: '验收操作', width: 120, fixed: 'right',
                            templet: function(row){
                                //return '<a class="layui-btn layui-btn-xs" lay-event="edit">维护</a>';
                                if(row.status==0){
                                    return '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="problem">问题标记</a>';
                                }else{
                                    return '';
                                }

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
                }else if(obj.event === 'show_img'){
                    $('#show_big').attr('src',data.brand_pic);
                    //console.log($('#show_big').attr('url'));
                    layer.open({
                        type:1,
                        title: false,
                        scrollbar: false,
                        closeBtn: 0,
                        //content: ['浏览器滚动条已锁','no'],
                        shadeClose: true,
                        area:'600px',
                        skin: 'layui-layer-nobg', //没有背景色
                        shadeClose: true,
                        content:$('#show_big')
                    })
                }else if(obj.event === 'check'){
                    $.ajax({
                        url:"{{url('admins/problem/check/')}}/"+data.id,
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

                }else if(obj.event === 'in'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'编辑信息',
                        area:['800px','600px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/purchase_warehouse_info/')}}/"+data.relate_id+"/edit"
                    });


                }
            });






        });

    </script>
@endsection
