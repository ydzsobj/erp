@extends('erp.father.father')
@section('content')
<style>
    .layui-input-width{
        width:150%;
    }
</style>
    <div class="layui-fluid">
        <form class="layui-form" action="" lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>店铺名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>店铺域名</label>
                <div class="layui-input-inline">
                    <input type="text" name="api_domain"  lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>API秘钥</label>
                <div class="layui-input-inline">
                    <input type="text" name="api_key" lay-verify="required" lay-reqtext="不能为空" placeholder="请输入API秘钥" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>API密码</label>
                <div class="layui-input-inline">
                    <input type="text" name="api_secret"  lay-verify="required" lay-reqtext="不能为空" placeholder="请输入" autocomplete="off" class="layui-input layui-input-width">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label"> <span style="color:red;">* </span>国家/地区</label>
                <div class="layui-input-inline">
                    <select name="country_id">
                        @foreach ($countries as $key=>$country)
                        <option value="{{ $key }}">{{ $country['name'] }}</option>
                        @endforeach

                    </select>
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
        }).use(['form','upload'], function(){
            var form = layui.form
                ,upload = layui.upload;
            var $=layui.jquery;

            //监听提交
            form.on('submit(form)', function(data){
                $.ajax({
                    url:"{{ route('shopify_accounts.store') }}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg.success){
                            layer.msg('成功！',{icon:1,time:2000},function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg('失败！',{icon:2,time:2000});
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
