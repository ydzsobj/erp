@extends('erp.father.father')
@section('content')
<div class="layui-form" lay-filter="layuiadmin-form-role" id="layuiadmin-form-role" style="padding: 20px 30px 0 0;">
    <div class="layui-fluid">
        <div class="demoTable">
            搜索ID或名称：
            <div class="layui-inline">
                <input class="layui-input" name="id" id="searchReload" autocomplete="off">
            </div>
            <button class="layui-btn" data-type="reload">搜索</button>
        </div>
        <table id="list" lay-filter="list"></table>
    </div>
    <input class="layui-input" name="id_1" lay-verify="username" id="demoReload_1" type="hidden" autocomplete="off">
    <div class="layui-form-item layui-hide">
        <button class="layui-btn" lay-submit lay-filter="showSubmit" id="showSubmit">提交</button>
    </div>
</div>

@endsection
@section('js')
    <script>

        layui.use(['table','layer','form'], function(){
            var table = layui.table,
                layer = layui.layer,
                form=layui.form,
                $=layui.jquery;

            //渲染实例
            table.render({
                elem: '#list'
                ,height: 525
                ,url: "{{url('/api/product_goods')}}" //数据接口
                ,id: 'listReload'
                ,count: 10000
                ,limit: 10
                ,limits: [10,20,30,50,100,300,500,1000,2000,5000,10000]
                ,page: true //开启分页
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left'}
                    ,{field: 'product_id', title: '产品ID', width: 100, sort: true}
                    ,{field: 'sku_code', title: 'SKU编码', width: 150}
                    ,{field: 'sku_name', title: '产品名称', width:180}
                    ,{field: 'sku_attr_names', title: '产品属性名', width:150}
                    ,{field: 'sku_attr_value_names', title: '产品规格值', width:150}
                    ,{field: 'sku_price', title: '销售价', width:80}
                ]]
                ,done: function(res, curr, count){
                $('#demoReload_1').val(res.data[0].id);
                $('tr[data-index="0"]').addClass('layui-table-click').siblings().removeClass('layui-table-click');
            }
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
                reload: function(){
                    var searchReload = $('#searchReload');

                    //执行重载
                    table.reload('listReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        ,where: {
                            keywords: searchReload.val(),
                        }
                    }, 'data');
                }
            };
            $('.demoTable .layui-btn').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });


            /* 点击添加 */
            table.on('row(list)', function(obj){
                var data = obj.data;
                console.log(data);
                var arr=[];
                arr.push(data.id);
                arr.push(data.sku_code);
                arr.push(data.sku_name);
                arr.push(data.sku_attr_names);
                arr.push(data.sku_attr_value_names);
                arr.push(data.sku_price);
                $('#demoReload_1').val(arr.join('|'));

                //$('#demoReload_1').val(arr);
                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });

            $('#demoReload').bind('input propertychange', function() {
                console.log($(this).val())
                table.reload('testReload', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        key: {
                            id: $(this).val()
                        }
                    }
                    ,done: function(res, curr, count){
                        if(res.data.lenght<1){
                            $('#demoReload_1').val('')
                        }else{
                            $('#demoReload_1').val(res.data[0])
                        }
                        $('tr[data-index="0"]').addClass('layui-table-click').siblings().removeClass('layui-table-click');
                    }
                }, 'data');

            });
            // form.verify({
            //     username: function(value, item){ //value：表单的值、item：表单的DOM对象
            //         console.log(value)
            //         if(value == ''){
            //             return '请选择商品';
            //         }
            //     }
            //
            //
            //
            //
            //
            // });
        });

    </script>

@endsection
