@extends('erp.father.father')
@section('content')
    <div style="width: 100%;height: calc(100% - 92px);">
        <div class="split-pane-warpper">
            <div class="pane pane-top" >
                <div class="layui-card-body">
                    <table id="data_list" lay-filter="data_list"></table>
                </div>
            </div>
        </div>
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
                elem: '#data_list'
                ,url: "{{url('api/purchase_pool/')}}/{{$id}}" //数据接口
                ,id: 'listReload'
                ,defaultToolbar: ['filter', 'exports', 'print']
                ,title: '库存数据表'
                ,page: true //开启分页
                ,count: 10000
                ,limit: 50
                ,limits: [50,100,300,500,1000,2000,5000,10000]
                ,cols: [[ //表头
                    {field: 'goods_position',title: '库位', width:100, fixed: 'left',templet:function (res) {
                            if(res.goods_position){ return res.goods_position;}else{ return '无'; }
                        }}
                    ,{field: 'id', title: 'ID', width:100, sort: true}
                    ,{field: 'goods_id', title: '商品ID', width:100, sort: true}
                    // ,{title: '商品名称', width:150,templet:function (res) {
                    //         return res.product_goods.sku_name;
                    //     }}
                    // ,{title: '属性名', width:100,templet:function (res) {
                    //         return res.product_goods.sku_attr_names;
                    //     }}
                    // ,{title: '属性值', width:100,templet:function (res) {
                    //         return res.product_goods.sku_attr_value_names;
                    //     }}
                    ,{title: '仓库名', width:120,templet:function (res) {
                            return res.warehouse.warehouse_name;
                        }}
                    ,{field: 'stock_num', title: '库存数量', width:100, style:'background-color: #eee; color: green;'}
                    ,{field: 'afloat_num', title: '在途数量', width:100, style:'background-color: #eee; color: blue;'}
                    ,{field: 'in_num', title: '入库数量', width:100}
                    ,{field: 'out_num', title: '出库数量', width:100}
                    ,{field: 'goods_sku', title: '商品编码', width:150,fixed:'right'}
                ]]
            });





        });

    </script>
@endsection
