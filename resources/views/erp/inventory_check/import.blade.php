@extends('erp.father.father')
@section('content')

    <form  class="layui-form" method="post" action="">
        {{csrf_field()}}
        <div class="layui-form-item" >
            <label class="layui-form-label">上传Excel：</label>
            <div class="layui-input-inline">
                <div class="layui-upload">
                    <input type="hidden" name="upload_file" autocomplete="off" class="layui-input">
                    <input type="hidden" name="warehouse_id" value="{{$id}}" class="layui-input">
                    <button type="button" name="file" class="layui-btn" id="upload"><i class="layui-icon"></i>上传文件</button>
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

    <div style="margin-top: 50px;text-align: center">
        <a href="{{ asset('admin/inventory_check.xlsx') }}"><span style="color:red;">下载盘点单模板</span></a>
    </div>

@endsection
@section('js')
    <script>
        //Demo
        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).use(['form','upload'], function(){
            var form=layui.form
                ,upload = layui.upload;
            var $=layui.jquery;

            //指定允许上传的文件类型
            upload.render({
                elem: '#upload'
                ,type: 'post'
                ,data: {"_token": "{{csrf_token()}}"}
                ,url: "{{url('admins/uploader/upload_data')}}"
                ,accept: 'file' //普通文件
                ,exts:'xls|excel|xlsx'
                ,done: function(res){
                    console.log(res);
                    if(res.code>0){
                        layer.msg(res.msg,{icon:2});
                    }else{
                        $('input[name=upload_file]').val(res.path);
                        layer.msg(res.msg,{icon:6});
                    }

                }
                ,error: function () {
                    //请求异常回调
                }
            });

            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                $.ajax({
                    url:"{{url('admins/inventory_check')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg.code=='0'){
                            layer.msg(msg.msg,{icon:1,time:5000},function () {
                                var index = parent.layer.getFrameIndex(window.name);
                                //刷新
                                parent.window.location = parent.window.location;
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg('导入失败！',{icon:2,time:2000});
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
