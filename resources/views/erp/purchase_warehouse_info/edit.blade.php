@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">采购数量</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="goods_num" autocomplete="off" disabled class="layui-input">
                    </div>
                    <label class="layui-form-label">订单数量</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="order_num" autocomplete="off" disabled class="layui-input">
                    </div>
                    <label class="layui-form-label">备货数量</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="plan_num" disabled autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">验货数量</label>
                    <div class="layui-input-inline" style="width: 300px;">
                        <input type="text" name="real_num" lay-verify="number" lay-reqtext="供应商名称不能为空" placeholder="请输入供应商名称" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid" style="color: #ff0000">* 实际到货数量</div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注信息</label>
                <div class="layui-input-block">
                    <textarea name="goods_text" placeholder="请输入内容" class="layui-textarea"></textarea>
                </div>
            </div>

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
        }).use(['form', 'upload'], function () {
            var form = layui.form
                , upload = layui.upload;
            var $ = layui.jquery;

            //表单初始赋值
            form.val('formData', {
                "goods_num": "{{$data->goods_num}}",
                "order_num": "{{$data->order_num}}",
                "plan_num": "{{$data->plan_num}}",
                "real_num": "{{$data->real_num}}",
                "goods_text": "{{$data->goods_text}}"
            });


            //监听提交
            form.on('submit(form)', function (data) {
                //layer.msg(JSON.stringify(data.field));
                $.ajax({
                    url: "{{url('admins/purchase_warehouse_info/in/')}}/{{$data->id}}",
                    type: 'post',
                    data: data.field,
                    datatype: 'json',
                    success: function (msg) {
                        if (msg == '0') {
                            layer.msg('操作成功！', {icon: 1, time: 2000}, function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        } else {
                            layer.msg('操作失败！', {icon: 2, time: 2000});
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
