@extends('erp.father.father')
@section('content')
    <style>
        .layui-table-cell{
            height:100px;
            line-height: 100px;
        }
    </style>
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-header layuiadmin-card-header-auto">
                <button class="layui-btn layuiadmin-btn-tags" data-type="add" onclick="category_show('添加品牌','{{url("admins/brand/create")}}',2,'700px','700px');">添加品牌</button>
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
        <table id="category_list" lay-filter="list" lay-size="lg"></table>
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
                elem: '#category_list'
                ,height: 500
                ,url: "{{url('api/brand')}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '品牌数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 10
                ,limits: [10,20,30,50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'brand_name', title: '品牌名称', width:180}
                    ,{field: 'brand_profile', title: '品牌简介', width:180}
                    ,{field: 'brand_sort', title: '排序', width: 80, sort: true}
                    ,{field: 'brand_pic', title: '品牌图片', width: 150, event: 'show_img', align:'center',templet: function(res){
                            return '<img src="'+ res.brand_pic +'"width="100px" height="100px" alt="">'
                    }}
                    ,{field: 'button', title: '操作', toolbar:'#button'}
                ]]
            });


            category_show = function category_show(title,url,type,w,h) {
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
                            url:"{{url('admins/brand/')}}/"+data.id,
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
                        area:['700px','700px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/brand/')}}/"+data.id+"/edit"
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
