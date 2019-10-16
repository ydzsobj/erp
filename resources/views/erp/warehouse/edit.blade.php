@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">仓库名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="warehouse_name" lay-verify="required" lay-reqtext="仓库名称不能为空" placeholder="请输入仓库名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">仓库编码</label>
                <div class="layui-input-inline">
                    <input type="text" name="warehouse_code" placeholder="请输入仓库编码" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">仓库地址</label>
                <div class="layui-input-block">
                    <input type="text" name="warehouse_address" placeholder="请输入仓库地址" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">仓库状态</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="checkbox" name="warehouse_status" lay-skin="switch" lay-text="ON|OFF" checked>
                    </div>
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
            $('#pic').attr('src', "{{$data->brand_pic}}");
            $('input[name=brand_pic]').val("{{$data->brand_pic}}");
            form.val('formData', {
                "warehouse_name": "{{$data->warehouse_name}}"
                , "warehouse_code": "{{$data->warehouse_code}}"
                , "warehouse_address": "{{$data->warehouse_address}}"
                , "warehouse_status": "{{$data->warehouse_status==1 ? 'on' : ''}}"

            });


            //监听提交
            form.on('submit(form)', function (data) {
                //layer.msg(JSON.stringify(data.field));
                if (data.field.warehouse_status == "on") {
                    data.field.warehouse_status = "1";
                } else {
                    data.field.warehouse_status = "0";
                }
                $.ajax({
                    url: "{{url('admins/warehouse/'.$data->id)}}",
                    type: 'put',
                    data: data.field,
                    datatype: 'json',
                    success: function (msg) {
                        if (msg == '0') {
                            layer.msg('修改成功！', {icon: 1, time: 2000}, function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        } else {
                            layer.msg('修改失败！', {icon: 2, time: 2000});
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
