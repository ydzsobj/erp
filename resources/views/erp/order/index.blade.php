@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <script type="text/html" id="toolbar">
            <div class="layui-btn-container">
                <button class="layui-btn layuiadmin-btn-tags" data-type="add" onclick="show('导入订单','{{url("admins/order/create")}}',2,'500px','500px');">导入订单</button>
            </div>
        </script>
        <div class="demoTable">
            搜索ID或名称：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="searchReload" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="list" lay-filter="list"></table>
    </div>





@endsection
@section('js')
    <script>

        layui.use(['table','layer'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;

            //渲染实例
            table.render({
                elem: '#list'
                ,url: "{{url('api/order')}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '产品数据表'
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,page: true //开启分页
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'order_sn', title: '订单编码', width: 180, fixed: 'left'}
                    ,{field: 'order_name', title: '收件人', width:100}
                    ,{field: 'order_phone', title: '电话', width:120}
                    ,{field: 'order_code', title: '邮编', width:80}
                    ,{field: 'order_province', title: '省', width:120}
                    ,{field: 'order_city', title: '市', width:120}
                    ,{field: 'order_county', title: '县', width:120}
                    ,{field: 'order_area', title: '区', width:120}
                    ,{field: 'order_address', title: '详细地址', width:220}

                    ,{field: 'ordered_at', title: '下单时间', width: 160, sort: true}
                    ,{field: 'button', title: '操作', toolbar:'#button', width: 250, fixed:'right'}
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
                reload: function(){
                    var searchReload = $('#searchReload');

                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: searchReload.val(),
                        }
                    }, 'data');
                }
            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
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
                        content:"{{url('admins/product/')}}/"+data.id
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){

                        $.ajax({
                            url:"{{url('admins/product/')}}/"+data.id,
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
                        content:"{{url('admins/product/')}}/"+data.id+"/edit"
                    });
                    //layer.alert('编辑行：<br>'+ JSON.stringify(data))
                }
            });



        });

    </script>

@endsection
