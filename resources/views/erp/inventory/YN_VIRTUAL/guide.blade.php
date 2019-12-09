@extends('erp.father.father')
@section('content')

<div class="layui-row" style="margin:30px;">
    <button type="button" class="layui-btn" id="in_store">入库</button>
    <button type="button" class="layui-btn" id="store">库存</button>
    <button type="button" class="layui-btn" id="out_store">出库</button>

</div>



@endsection

@section('js')

    <script>
          layui.use(['table', 'upload','layer', 'laydate'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery
                , tablePlug = layui.tablePlug //表格插件
                , testTablePlug = layui.testTablePlug ;// 测试js模块
                var upload = layui.upload;
                var laydate = layui.laydate;

                $("#in_store").click(function(){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'导入入库',
                        area: ['60%', '60%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{ route('inventory.yn_virtual_in_create') }}?warehouse_id={{ $warehouse_id }}",
                    });
                })

                $("#store").click(function(){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'库存',
                        area: ['95%', '95%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{ route('inventory.index') }}?warehouse_id={{ $warehouse_id }}",
                    });
                })

                $("#out_store").click(function(){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'出库',
                        area: ['90%', '90%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{ route('inventory.yn_virtual_out_create') }}?warehouse_id={{ $warehouse_id }}",
                    });
                })

          })
    </script>

@endsection

