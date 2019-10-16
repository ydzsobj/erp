@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action=""  lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">属性值名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="attr_value_name" lay-verify="required" lay-reqtext="属性值不能为空"
                           placeholder="请输入属性名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">英文名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="attr_value_english" lay-verify="required" lay-reqtext="英文名称不能为空"
                           placeholder="请输入英文名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">属性值编码</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="text" name="attr_value_code" autocomplete="off" class="layui-input" maxlength="2">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">属性展示</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="checkbox" name="attr_value_status" lay-skin="switch" lay-text="ON|OFF">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline" style="width: 50px;">
                    <input type="text" name="attr_value_sort" value="0" autocomplete="off" class="layui-input">
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
        }).use('form', function(){
            var form = layui.form;
            var $=layui.jquery;

            //表单初始赋值
            form.val('formData', {
                "attr_value_name": "{{$data->attr_value_name}}"
                ,"attr_value_english": "{{$data->attr_value_english}}"
                ,"attr_value_code": "{{$data->attr_value_code}}"
                ,"attr_value_sort": "{{$data->attr_value_sort}}"
                ,"attr_value_status" : "{{$data->attr_value_status==1 ? 'on' : ''}}"

            });

            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                if(data.field.attr_value_status == "on") {
                    data.field.attr_value_status = "1";
                } else {
                    data.field.attr_value_status = "0";
                }

                $.ajax({
                    url:"{{url('admins/attribute_value/'.$data->id)}}",
                    type:'put',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg=='0'){
                            layer.msg('修改成功！',{icon:1,time:2000},function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
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
