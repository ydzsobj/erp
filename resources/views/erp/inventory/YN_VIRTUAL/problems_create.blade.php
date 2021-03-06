@extends('erp.father.father')
@section('content')
<table id="data_list" lay-filter="data_list"></table>
@endsection

<!--筛选开始-->
<div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">请输入</label>
                    <div class="layui-input-block">
                        <div class="layui-inline" style="width:265px;">
                            <input class="layui-input" name="keywords" id="keywords" placeholder="产品名称/SKU编号"  autocomplete="off">
                        </div>
                    </div>
                </div>

                    <div class="layui-inline">
                            <a class="layui-btn" data-type="reload"  id='search'>查询</a>
                            &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
            </div>
        </form>
</div>

@section('js')
    <script>

        layui.use(['table', 'upload','layer', 'laydate'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;
                var upload = layui.upload;

                //点击搜索
              $('#search').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
                console.log('11');
                //执行重载

            });

            var active = {
                reload: function(){

                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: $("#keywords").val()
                        }
                    }, 'data');

                    table.reload('testReload', {
                       data:[]
                    });
                },

            };


            //渲染实例
            table.render({
                elem: '#data_list'
                ,url: "{{url('api/inventory_info')}}" //数据接口
                ,where: {
                    warehouse_id:  {{ $warehouse_id }},
                    out_status: 3
                }
                ,id: 'listReload'
                // ,toolbar: '#toolbarDemo'
                ,parseData: function(res){ //res 即为原始返回的数据
                        return {
                            "code": res.code, //解析接口状态
                            "msg": res.msg, //解析提示文本
                            "count": res.count, //解析数据长度
                            "data": res.data.data //解析数据列表
                        };
                    }
                ,defaultToolbar: []
                ,title: '库存数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {type: 'checkbox', width:50}
                        ,{field:'created_at', width:160, title: '业务时间', sort:true}
                        ,{title: '产品名称',  templet: function(res){
                            return res.sku.sku_name;
                        }}
                        ,{title: '属性值', width:90, templet: function(res){
                            return res.sku.sku_attr_value_names;
                        }}
                        ,{field:'goods_sku', title: 'SKU编码', width:150}
                        ,{field:'in_num', width:80, title: '数量',style:'color:green'}
                        ,{field:'stock_type', title: '业务类型', width:90}
                        ,{field:'import_order_sn', title: '订单编号', width:120,}

                        ,{field:'user_id', title: '操作人',  width:100,templet: function(res){
                            return res.admin.admin_name;
                        }}
                        ,{title:'操作', width:100, templet:function(){

                            html_str = '<a class="layui-btn layui-btn-xs" lay-event="set_status">处理</a>';
                            return html_str;
                        }}

                ]]
            });


            //监听工具条
            table.on('tool(data_list)', function(obj){
                var data = obj.data;

                if(obj.event === 'set_status'){
                    //设置
                    layer.confirm('确定要标记为已处理吗？', function(index){
                        $.ajax({
                            type:'POST',
                            url: "/admins/inventory/" + data.id +"/update_status",
                            data:{
                                _token: "{{ csrf_token() }}" ,
                                _method: 'put',
                                warehouse_id : {{ $warehouse_id }},
                                action: 'remove_problem'
                            },
                            dataType:"json",
                            success:function(msg){
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                    if(msg.success){
                                       table.reload('listReload');
                                    }
                            },
                            error: function(data){
                                layer.msg('请求接口失败',{icon:2,time:2000});
                            }

                        })
                    });

                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){



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
                }
            });

            //监听头部工具条
            table.on('toolbar(data_list)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(obj);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

                var checkStatus = table.checkStatus(obj.config.id);

                console.log(checkStatus.data);

                if(layEvent === 'in_store'){ //

                }else if(layEvent == 'batch_out_store'){
                    //批量审核
                    if(checkStatus.data.length == 0){
                        layer.msg('请先勾选数据');
                        return false;
                    }

                    var selected_rows = checkStatus.data;
                    var selected_ids = [];
                    for(var i=0;i<selected_rows.length;i++){
                        selected_ids.push(selected_rows[i].id);
                    }
                    console.log(selected_ids);

                    layer.confirm('确定出库吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: "{{ route('inventory.yn_virtual_out') }}",
                            data:{ _token: "{{ csrf_token() }}" ,out_data: selected_rows },
                            dataType:"json",
                            success:function(msg){
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                    if(msg.success){
                                       table.reload('listReload');
                                    }
                            },
                            error: function(data){
                                layer.msg('请求接口失败',{icon:2,time:2000});
                            }

                        })
                    });

                }
            });

        });

    </script>
@endsection

{{-- <script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="batch_out_store" >处理</button>
    </div>
  </script> --}}
