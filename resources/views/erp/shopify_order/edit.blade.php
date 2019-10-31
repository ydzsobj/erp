@extends('erp.father.father')
@section('content')
<style>
    .layui-input-width{
        width:150%;
    }

    .layui-table-cell{
        overflow:inherit;
        height: 40px;
    }
    .layui-table-body,.layui-table-box{
        overflow:inherit;
    }
</style>
<div class="layui-container">
    <div class="layui-row">
    <div>
        订单编号： {{ $detail->sn }}
    </div>
        <div>
        下单时间： {{ $detail->submit_order_at }}
        </div>
        <div class="layui-col-md3">
        状态 : {{ $status_list[$detail->status] }}
        </div>
    </div>
</div>
    <div class="layui-fluid">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <table id="sku_table" lay-filter="sku_filter"></table>
            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>收件人</label>
                <div class="layui-input-inline">
                    <input type="text" name="receiver_name" value="{{ $detail->receiver_name }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>收件人电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="receiver_phone" value="{{ $detail->receiver_phone }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                    <label class="layui-form-label"> <span style="color:red;">* </span>省市区</label>
                    <div class="layui-input-inline">
                        <input type="text" name="province" value="{{ $detail->province }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                            <input type="text" name="city" value="{{ $detail->city }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="area" value="{{ $detail->area }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>邮编</label>
                <div class="layui-input-inline">
                    <input type="text" name="postcode" value="{{ $detail->postcode }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>地址</label>
                <div class="layui-input-inline">
                    <input type="text" name="address1" value="{{ $detail->address1 }}" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            {{-- <div class="layui-form-item">
                    <label class="layui-form-label"> 地址2</label>
                    <div class="layui-input-inline">
                        <input type="text" name="address2" value="{{ $detail->address2 }}" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                    </div>
            </div> --}}

            {{-- <div class="layui-form-item">
                    <label class="layui-form-label"> 公司名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="company" value="{{ $detail->company }}" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                    </div>
            </div> --}}

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="form">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>

    </div>


@endsection
@section('js')
    <script>
        //Demo
        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).use(['form','upload','table'], function(){
            var form = layui.form
                ,table = layui.table
                ,upload = layui.upload;

            var $=layui.jquery;

            table.render({
                elem: '#sku_table'
                ,data: {!! $sku_info !!}
                ,cols: [[ //
                    {field: 'sku_code', title:'SKU',width:180}
                    ,{field: 'sku_name', title: '商品名'}
                    ,{field: 'test', title: '属性',width:150, style:"height:38px;",
                        templet:function(row){
                            var options = '<select name="sku_ids[' + row.order_sku_id +']">';

                            var product_skus = row.product_skus;
                            for(var i=0;i<product_skus.length;i++){
                                var selected = product_skus[i].sku_code == row.sku_code ? 'selected' : '';
                                options += '<option value=' + product_skus[i].sku_code + ' ' + selected + '>' + product_skus[i].sku_attr_value_names +'</option>';
                            }

                            return options;
                        }
                    }
                    ,{field: 'amount', title: '数量', width:80,sort:true}
                ]],

        });

            //监听提交
            form.on('submit(form)', function(data){
                $.ajax({
                    url:"{{url('admins/orders/'.$detail->id)}}",
                    type:'put',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg.success){
                            layer.msg('修改成功！',{icon:1,time:2000},function (index) {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                // parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg('修改失败！',{icon:2,time:2000});
                        }
                    },
                    error: function(data){
                        var errors = JSON.parse(data.responseText).errors;
                        var msg = '';
                        for(var a in errors){
                            msg += errors[a][0]+'<br />';
                        }
                        layer.msg(msg,{icon:2,time:2000});
                    }
                });
                return false;
            });



        });
    </script>
@endsection
