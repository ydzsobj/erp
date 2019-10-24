@extends('erp.father.father')
@section('content')
    <style>
    </style>
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header layuiadmin-card-header-auto">
                <button class="layui-btn layuiadmin-btn-tags" data-type="add" onclick="create_show('添加店铺','{{url("admins/shopify_accounts/create")}}',2,'700px','700px');">添加店铺</button>
            </div>
            <div class="layui-card-body">
                <table id="LAY-app-content-tags" lay-filter="LAY-app-content-tags"></table>
                <script type="text/html" id="layuiadmin-app-cont-tagsbar">
                    <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
                </script>
            </div>

        </div>
        <div class="demoTable">
            搜索ID或名称：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="searchReload" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="data_list" lay-filter="list" lay-size="lg"></table>
    </div>
    <img src="" id="show_big" width="100%" style="display: none">
    <script type="text/html" id="button" >
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="import_order">抓取订单</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>


@endsection
@section('js')
    <script>

        layui.use(['table','layer'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;

            //渲染实例
            table.render({
                elem: '#data_list'
                ,height: 500
                ,url: "/api/shopify_accounts" //数据接口
                ,parseData: function(res){ //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data.data //解析数据列表
                    };
                }
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: []
                ,title: '品牌数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 10
                ,limits: [10,20,30,50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'name', title: '店铺名称'}
                    ,{field: 'api_domain', title: '店铺域名'}
                    ,{field: 'api_key', title: 'API密钥'}
                    ,{field: 'api_secret', title: 'API密码',  align:'center'}
                    ,{field: 'country_name', title: '国家/地区', width: 150, align:'center'}
                    ,{field: 'button', title: '操作', toolbar:'#button'}
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
                        content:"{{url('admins/brand/')}}/"+data.id
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){

                        $.ajax({
                            url:"{{url('admins/shopify_accounts/')}}/"+data.id,
                            type:'delete',
                            data:{"_token":"{{csrf_token()}}"},
                            datatype:'json',
                            success:function (msg) {
                                if(msg.success){
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
                        area:['700px','700px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/shopify_accounts/')}}/"+data.id+"/edit"
                    });
                    //layer.alert('编辑行：<br>'+ JSON.stringify(data))
                }else if(obj.event === 'import_order'){
                    //抓取订单数据
                    $.ajax({
                            url:"{{ route('shopify_account.create_orders') }}",
                            type:'post',
                            data:{"_token":"{{csrf_token()}}","id":data.id},
                            datatype:'json',
                            success:function (msg) {
                                if(msg.success){
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                }else{
                                    layer.msg('失败！',{icon:2,time:2000});
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
