@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <div class="demoTable">
            搜索ID或名称：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="searchReload" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="data_list" lay-filter="list"></table>
    </div>
    <img src="" id="show_big" width="100%" style="display: none">
    <script type="text/html" id="button" >
        <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="detail">查看</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
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
                ,url: "{{url('admins/admin/log')}}" //数据接口
                ,method:'post'
                ,where:{"_token":"{{csrf_token()}}"}
                ,id: 'listReload'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '管理员数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'username', title: '管理员账号', width:150}
                    ,{field: 'password', title: '登录错误', width:150}
                    ,{field: 'message', title: '提示信息', width:200}
                    ,{field: 'admin_ip', title: '登录IP', width:150}
                    ,{field: 'created_at', title: '登录时间', width:160}

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
                        content:"{{url('admins/admin/')}}/"+data.id
                    });
                    //layer.msg('ID：'+ data.id + ' 的查看操作');
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){

                        $.ajax({
                            url:"{{url('admins/admin/')}}/"+data.id,
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
                        area:['500px','400px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/admin/')}}/"+data.id+"/edit"
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
                }
            });



        });

    </script>
@endsection
