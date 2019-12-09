@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">供应商名称</label>
                    <div class="layui-input-inline" style="width: 300px;">
                        <input type="text" name="supplier_name" lay-verify="required" lay-reqtext="供应商名称不能为空" placeholder="请输入供应商名称" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">编号</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="supplier_code" placeholder="请输入编号" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">供应商链接</label>
                <div class="layui-input-block">
                    <input type="text" name="supplier_url" placeholder="请输入供应商链接" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">供应商地点</label>
                <div class="layui-input-inline">
                    <input type="text" name="supplier_address" placeholder="请输入供货地点" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">联系人</label>
                    <div class="layui-input-inline">
                        <input type="text" name="supplier_person" lay-verify="required" placeholder="请输入联系人姓名" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">联系电话</label>
                    <div class="layui-input-inline">
                        <input type="text" name="supplier_phone" placeholder="请输入联系人电话" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">开户银行</label>
                    <div class="layui-input-inline">
                        <input type="text" name="bank_name" placeholder="请输入开户银行" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">银行账户</label>
                    <div class="layui-input-inline">
                        <input type="text" name="bank_account" placeholder="请输入银行账户" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">公司税号</label>
                    <div class="layui-input-inline" style="width: 250px;">
                        <input type="text" name="tax_number" placeholder="请输入公司税号" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">公司税率</label>
                    <div class="layui-input-inline" style="width: 100px;">
                        <input type="text" name="tax_rate" placeholder="请输入公司税率" autocomplete="off" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">备注信息</label>
                <div class="layui-input-block">
                    <textarea name="supplier_text" placeholder="请输入内容" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="checkbox" name="supplier_status" lay-skin="switch" lay-text="ON|OFF" checked>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline" style="width: 50px;">
                    <input type="text" name="supplier_sort" value="0" autocomplete="off" class="layui-input">
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
                "supplier_name": "{{$data->supplier_name}}"
                , "supplier_url": "{{$data->supplier_url}}"
                , "supplier_address": "{{$data->supplier_address}}"
                , "supplier_person": "{{$data->supplier_person}}"
                , "supplier_phone": "{{$data->supplier_phone}}"
                , "supplier_text": "{{$data->supplier_text}}"
                , "supplier_sort": "{{$data->supplier_sort}}"
                , "supplier_status": "{{$data->supplier_status==1 ? 'on' : ''}}"

            });


            //监听提交
            form.on('submit(form)', function (data) {
                //layer.msg(JSON.stringify(data.field));
                if (data.field.supplier_status == "on") {
                    data.field.supplier_status = "1";
                } else {
                    data.field.supplier_status = "0";
                }
                $.ajax({
                    url: "{{url('admins/supplier/'.$data->id)}}",
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
