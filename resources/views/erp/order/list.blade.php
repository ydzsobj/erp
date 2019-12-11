@extends('erp.father.father')
@section('content')
    <div class="layui-row" style="margin-top:5px;">
        <form class="layui-form" action="">

            <div class="layui-inline">
                <label class="layui-form-label">请输入</label>
                <div class="layui-input-block">
                    <div class="layui-inline" style="width:300px;">
                        <input class="layui-input" name="keywords" id="searchReload" placeholder="订单编号/运单编号/收货人姓名电话详细地址"  autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-inline">
                    <select name="order_status" id="order_status">
                        <option value="0">未导入</option>
                        <option value="1">已导入</option>
                        <option value="2">已确定</option>
                    </select>
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label">导入时间</label>
                <div class="layui-input-block">
                    <div class="layui-inline">
                        <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间">
                    </div>-
                    <div class="layui-inline">
                        <input class="layui-input" name="end_date" id="end_date" placeholder="结束时间">
                    </div>
                </div>
            </div>

            <div class="layui-row demoTable" style="margin-top:10px;">
                <a class="layui-btn" data-type="reload" style="margin-left:600px;" id='search'>搜索</a>
                &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>

        </form>
    </div>

    <div class="layui-fluid">
        <table id="list" lay-filter="list"></table>
    </div>
    <script type="text/html" id="status">
        @{{# if(d.order_status == 0){ }} <div style="color: #ff0000">未导入</div> @{{# }else if(d.order_status == 1){  }} <div style="color: #0000FF">已导入</div>  @{{# }else{  }} <div style="color: #008000">已确定</div> @{{# }  }}
    </script>
    <script type="text/html" id="order_lock">
        @{{# if(d.order_lock == 0){ }} <div style="color: #ff0000">未锁定</div> @{{# }else if(d.order_lock == 1){  }} <div style="color: #008000">已锁定</div>  @{{# }else{  }} <div>未知</div> @{{# }  }}
    </script>
    <script type="text/html" id="order_used">
        @{{# if(d.order_used == 0){ }} <div style="color: #ff0000">未占用</div> @{{# }else if(d.order_used == 1){  }} <div style="color: #008000">已占用</div>  @{{# }else{  }} <div>未知</div> @{{# }  }}
    </script>

@endsection
@section('js')
    <script>

        layui.use(['table','layer', 'laydate'], function(){
            var table = layui.table,
                layer = layui.layer,
                laydate = layui.laydate;
                $=layui.jquery;

            //渲染实例
            table.render({
                elem: '#list'
                ,url: "{{url('api/order/list')}}" //数据接口
                ,id: 'listReload'
                ,title: '产品数据表'
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,page: true //开启分页
                ,height: 'full-150'
                ,cols: [[ //表头
                    {field: 'order_sn', title: '订单编码', width: 200, fixed: 'left'}
                    ,{title: '状态', width: 80, fixed: 'left',templet:'#status'}
                    ,{field: 'id', title: 'ID', width:80, sort: true,}
                    ,{field: 'order_name', title: '收件人', width:100}
                    ,{field: 'order_phone', title: '电话', width:120}
                    ,{field: 'order_code', title: '邮编', width:80}
                    ,{field: 'order_province', title: '省', width:120}
                    ,{field: 'order_city', title: '市', width:120}
                    ,{field: 'order_area', title: '区', width:120}
                    ,{field: 'order_address', title: '详细地址', width:220}
                    ,{field: 'ordered_at', title: '下单时间', width: 160, sort: true}
                    ,{field: 'created_at', title: '导入时间', width: 160, sort: true}
                    ,{title: '锁定', width: 80, fixed: 'right',templet:'#order_lock'}
                    ,{title: '占用', width: 80, fixed: 'right',templet:'#order_used'}
                    ,{title: '仓库', width: 80, fixed: 'right',templet:function(res){
                            if(res.warehouse){ return res.warehouse.warehouse_name }else{ return ''; }
                        }}
                ]]
            });


            show = function show(title,url,type,w,h) {
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
                //批量审核
                getCheckData:function(){
                    var checkStatus = table.checkStatus('listReload');
                    if(checkStatus.data.length==0){
                        parent.layer.msg('请先选择要生成的数据行！', {icon: 2});
                        return ;
                    }
                    var codeId= "";
                    for(var i=0;i<checkStatus.data.length;i++){
                        codeId += checkStatus.data[i].id+",";
                    }
                    parent.layer.msg('生成中...', {icon: 16,shade: 0.3,time:5000});

                    $.ajax({
                        type:"POST",
                        url: "{{url('admins/order/create_order_pool')}}",
                        data:{"ids":codeId,"_token":"{{csrf_token()}}"},
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

                }

            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            //渲染时间
            laydate.render({
                elem: '#start_date'
                ,type: 'datetime'
            });

            laydate.render({
                elem: '#end_date'
                ,type: 'datetime'
            });



            //监听工具条
            table.on('tool(list)', function(obj){
                var data = obj.data;

                if(obj.event === 'detail'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'基本信息',
                        area:['600px','100%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/order/')}}/"+data.id
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                }else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){

                        $.ajax({
                            url:"{{url('admins/order/')}}/"+data.id,
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
                        area:['100%','100%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/order/')}}/"+data.id+"/edit"
                    });
                    //layer.alert('编辑行：<br>'+ JSON.stringify(data))
                }
            });



        });

    </script>

@endsection
