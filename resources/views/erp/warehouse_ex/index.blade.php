@extends('erp.father.father')
@section('content')
    <div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
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
                            <option value="0">未出库</option>
                            <option value="1">拣货中</option>
                            <option value="2">已出库</option>
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
            </div>

            <div class="layui-row demoTable">
                <a class="layui-btn" data-type="reload" style="margin-left:600px;" id='search'>搜索</a>
                &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>

        </form>
    </div>

    <div class="layui-fluid">
        <script type="text/html" id="toolbar">
            <div class="layui-btn-container demoTable">
                <button class="layui-btn" data-type="getCheckData">批量生成拣货单</button>
{{--                <button class="layui-btn layui-btn-warm" onclick="show('查看采购汇总单','{{url("admins/order/order_pool")}}',2,'100%','100%');">查看采购汇总单</button>--}}
                <button class="layui-btn layuiadmin-btn-tags layui-btn-normal" onclick="show('导入出库运单','{{url("admins/warehouse_ex/create")}}',2,'500px','500px');">导入出库运单</button>
            </div>
        </script>
        <table id="list" lay-filter="list"></table>
    </div>
    <script type="text/html" id="status">
        @{{# if(d.ex_status == 0){ }} <div style="color: #ff0000">未出库</div> @{{# }else if(d.ex_status == 1){  }} <div style="color: #0000FF">拣货中</div>  @{{# }else{  }} <div style="color: #008000">已出库</div> @{{# }  }}
    </script>
    <script type="text/html" id="order_lock">
        @{{# if(d.order_lock == 0){ }} <div style="color: #ff0000">未锁定</div> @{{# }else{  }} <div style="color: #008000">已锁定</div> @{{# }  }}
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
                ,url: "{{url('api/warehouse_ex')}}" //数据接口
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
                    {type:'checkbox', fixed: 'left'}
                    ,{field: 'order_sn', title: '订单号', width: 150, fixed: 'left'}
                    ,{field: 'yunlu_sn', title: '运单号', width: 150, fixed: 'left'}
                    ,{title: '状态', width: 80, fixed: 'left',templet:'#status'}
                    ,{title: '锁定', width: 80, fixed: 'left',templet:'#order_lock'}
                    ,{field: 'id', title: 'ID', width:80, sort: true,}
                    ,{field: 'order_name', title: '收件人', width:100}
                    ,{field: 'order_phone', title: '电话', width:120}
                    ,{field: 'order_code', title: '邮编', width:80}
                    ,{field: 'order_province', title: '省', width:120}
                    ,{field: 'order_city', title: '市', width:120}
                    ,{field: 'order_county', title: '县', width:120}
                    ,{field: 'order_area', title: '区', width:120}
                    ,{field: 'order_address', title: '详细地址', width:220}
                    ,{field: 'ordered_at', title: '下单时间', width: 160, sort: true}
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
                        if(checkStatus.data[i].order_lock<1){
                            parent.layer.msg('有订单未锁库，请核查订单状态！', {icon: 2});
                            return ;
                        }
                        codeId += checkStatus.data[i].id+",";
                    }
                    parent.layer.msg('生成中...', {icon: 16,shade: 0.3,time:5000});
                    json = JSON.stringify(checkStatus);
                    layui.use('layer', function () {
                        layer.open({
                            skin:'layui-layer-nobg',
                            type:2,
                            title:'编辑信息',
                            area:['100%','100%'],
                            fixed:false,
                            maxmin:true,
                            content:"{{url('admins/warehouse_pick/create')}}",
                            success:function (layero,index) {
                                var iframe = window['layui-layer-iframe' + index];
                            }
                        });
                    });

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







        });

    </script>

@endsection
