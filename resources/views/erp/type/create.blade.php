@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">类型名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="type_name" lay-verify="required" lay-reqtext="类型名称不能为空" placeholder="请输入类型名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">英文名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="type_english" lay-verify="required" lay-reqtext="英文名称不能为空" placeholder="请输入英文名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item" lay-filter="component-form-element">
                <label class="layui-form-label">属性名称</label>
                <div class="layui-input-block">
{{--                    @foreach($attribute as $value)--}}
{{--                        <input type="checkbox" value="{{$value->id}}" name="attr_ids[]" title="{{$value->attr_name}}">--}}
{{--                    @endforeach--}}
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline" style="width: 50px;">
                    <input type="text" name="type_sort" value="0" autocomplete="off" class="layui-input">
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


            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                $.ajax({
                    url:"{{url('admins/type')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg=='0'){
                            layer.msg('添加成功！',{icon:1,time:2000},function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg('添加失败！',{icon:2,time:2000});
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
