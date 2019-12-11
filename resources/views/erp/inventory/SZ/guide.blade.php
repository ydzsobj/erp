@extends('erp.father.father')
@section('content')

<div class="layui-row" style="margin:30px;">
    <button type="button" class="layui-btn layui-btn-lg" id="in_store"> <span class="iconfont icon-ruku"></span> 验货入库</button>
    <button type="button" class="layui-btn layui-btn-lg" id="store"><span class="iconfont icon-icon-p_dangqiankucun"></span>  产品库存</button>
    <button type="button" class="layui-btn layui-btn-lg" id="out_store"><span class="iconfont icon-chuku"></span> 拣货出库</button>

</div>
<div style="font-size: 40px;text-align: center;">
<span class="iconfont icon-icon-p_dangqiankucun" style="font-size: 120px; color: #d2d2d2;"></span>
<p style="color: #d2d2d2;">深圳仓</p>
</div>



@endsection

@section('js')
    <script>
        layui.use(['table','layer'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery;

            $("#in_store").click(function(){
                layer.open({
                    skin:'layui-layer-nobg',
                    type:2,
                    title:'验货入库',
                    area: ['100%', '100%'],
                    fixed:false,
                    maxmin:true,
                    content:"{{url('admins/purchase_warehouse/')}}/{{$id}}",
                });
            });

            $("#store").click(function(){
                layer.open({
                    skin:'layui-layer-nobg',
                    type:2,
                    title:'库存',
                    area: ['100%', '100%'],
                    fixed:false,
                    maxmin:true,
                    content:"{{url('admins/inventory/')}}/{{$id}}",
                });
            });


            $("#out_store").click(function(){
                layer.open({
                    skin:'layui-layer-nobg',
                    type:2,
                    title:'出库',
                    area: ['95%', '95%'],
                    fixed:false,
                    maxmin:true,
                    content:"",
                });
            });



        })
    </script>


@endsection

