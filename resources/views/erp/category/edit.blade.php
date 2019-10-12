@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action=""  lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">分类名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="category_name" lay-verify="required" lay-reqtext="分类名称不能为空" placeholder="请输入分类名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">父级分类</label>
                <div class="layui-input-inline">
                    <select name="parent_id" lay-filter="aihao">
                        <option value="0">顶级分类</option>
                        @foreach($category as $value)
                        <option value="{{$value->id}}" @if($value->id==$data->id) selected @endif>{{$value->category_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">类型</label>
                <div class="layui-input-inline">
                    <select name="type_id" lay-filter="aihao">
                        <option value="0">请选择类型</option>
                        @foreach($type as $value)
                            <option value="{{$value->id}}" @if($value->id==$data->type_id) selected @endif>{{$value->type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">分类编码</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="category_code" value="0" autocomplete="off" class="layui-input" maxlength="2">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline" style="width: 50px;">
                    <input type="text" name="category_sort" value="0" autocomplete="off" class="layui-input">
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
                "category_name": "{{$data->category_name}}"
                ,"parent_id": "{{$data->parent_id}}"
                ,"category_code": "{{$data->category_code}}"
                ,"category_sort": "{{$data->category_sort}}"

            });

            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                $.ajax({
                    url:"{{url('admins/category/'.$data->id)}}",
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
