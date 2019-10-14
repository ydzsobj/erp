@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action=""  lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">计量单位</label>
                <div class="layui-input-inline">
                    <input type="text" name="unit_name" lay-verify="required" lay-reqtext="计量单位不能为空" placeholder="请输入计量单位" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">编码</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="unit_code" value="0" autocomplete="off" class="layui-input" maxlength="2">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block" style="width: 350px;">
                    <input type="radio" name="unit_status" value="1" title="启用" @if($data->unit_status ==1 ) checked="checked" @endif>
                    <input type="radio" name="unit_status" value="0" title="禁用" @if($data->unit_status ==0 ) checked="checked" @endif>
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
                "unit_name": "{{$data->unit_name}}"
                ,"unit_code": "{{$data->unit_code}}"
            });

            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                $.ajax({
                    url:"{{url('admins/product_unit/'.$data->id)}}",
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
                    error: function(XmlHttpRequest, textStatus, errorThrown){
                        layer.msg('error!',{icon:2,time:2000});
                    }
                });
                return false;
            });



        });
    </script>
@endsection
