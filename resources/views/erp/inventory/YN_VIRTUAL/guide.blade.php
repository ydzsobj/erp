@extends('erp.father.father')
@section('content')

<div class="layui-row" style="margin:30px;">
    <button type="button" class="layui-btn layui-btn-lg layui-btn-radius" id="in_store"><span class="iconfont icon-ruku"></span> 入库</button>
    <button type="button" class="layui-btn layui-btn-lg layui-btn-radius" id="store"> <span class="iconfont icon-icon-p_dangqiankucun"></span> 库存</button>
    <button type="button" class="layui-btn layui-btn-lg layui-btn-radius" id="out_store"> <span class="iconfont icon-chuku"></span> 出库</button>
    <button type="button" class="layui-btn layui-btn-lg layui-btn-radius" id="problems"> <span class="iconfont icon-wenti"></span> 问题件</button>

</div>
<div style="font-size: 40px;text-align: center;">
<span class="iconfont icon-icon-p_dangqiankucun" style="font-size: 120px; color: #d2d2d2;"></span>
<p style="color: #d2d2d2;">印尼虚拟仓</p>
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
                        title:'入库',
                        area: ['95%', '95%'],
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

                $("#problems").click(function(){
                    layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'问题件',
                        area: ['90%', '90%'],
                        fixed:false,
                        maxmin:true,
                        content:"{{ route('inventory.problems_create') }}?warehouse_id={{ $warehouse_id }}",
                    });
                })

          })
    </script>

@endsection

