@extends('erp.father.father')
@section('content')

    <style type="text/css">
        .layui-table-cell,.layui-table-col-special {
            height: auto;
        }
        th>.laytable-cell-checkbox i{
            display: none;
        }
    </style>


    <div class="layui-fluid">
        <script type="text/html" id="toolbar">
            <div class="layui-btn-container demoTable">
                <button class="layui-btn" data-type="getCheckData">生成采购单</button>
            </div>
        </script>
        <table id="list" lay-filter="list"></table>
    </div>
    <script type="text/html" id="inventory">
        <span style="color: #ff0000">
        @{{# d.inventory.map(item=>{ }}
        @{{ item.warehouse.warehouse_name }}：[库存可用：@{{ item.stock_unused_num }}； 在途备货可用：@{{ item.plan_unused_num }}；] <br/>
        @{{# }) }}
        </span>
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
                elem: '#list'
                ,url: "{{url('api/purchase_pool')}}" //数据接口
                ,id: 'listReload'
                ,toolbar: '#toolbar'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '计量单位数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 100
                ,limits: [100,300,500,1000,2000,5000,10000]
                ,height: 'full-50'
                ,cols: [[ //表头
                    {type:'checkbox'}
                    ,{field: 'id', title: 'ID', width: 80}
                    ,{field: 'goods_sku', title: 'SKU编码', width: 150}
                    ,{field: 'purchase_num', title: '建议采购数量', width:120}
                    ,{field: 'order_num', title: '订单数量', width:120}
                    ,{field: 'goods_name', title: '商品名称', width:180}
                    ,{field: 'goods_english', title: '英文名称', width:180}
                    ,{field: 'goods_attr_name', title: '属性名', width: 100}
                    ,{field: 'goods_attr_value', title: '属性值', width: 100}
                    ,{field: 'goods_price', title: '商品价格', width: 100}
                    ,{title: '仓储数量', width:350 , templet:'#inventory'}
                ]]
            });





            var active = {

                //批量审核
                getCheckData:function(){
                    var checkStatus = table.checkStatus('listReload');
                    if(checkStatus.data.length==0){
                        parent.layer.msg('请先选择要生成的数据行！', {icon: 2});
                        return ;
                    }
                    json = JSON.stringify(checkStatus);

                    layui.use('layer', function () {
                        layer.open({
                            skin:'layui-layer-nobg',
                            type:2,
                            title:'编辑信息',
                            area:['100%','100%'],
                            fixed:false,
                            maxmin:true,
                            content:"{{url('admins/purchase_pool/create')}}",
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



            //监听工具条
            table.on('tool(list)', function(obj){
                var data = obj.data;

                if(obj.event === 'detail'){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'基本信息',
                        area:['800px','600px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/purchase_pool/')}}/"+data.goods_sku
                    });
                    //layer.msg('ID：'+ data.goods_sku + ' 的查看操作');
                } else if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){

                        $.ajax({
                            url:"{{url('admins/product_unit/')}}/"+data.id,
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
                        content:"{{url('admins/product_unit/')}}/"+data.id+"/edit"
                    });
                    //layer.alert('编辑行：<br>'+ JSON.stringify(data))
                }
            });



        });

    </script>
@endsection
