@extends('erp.father.father')
@section('content')
    <div class="layui-fluid">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">品牌名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="brand_name" lay-verify="required" lay-reqtext="品牌名称不能为空" placeholder="请输入品牌名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">品牌简介</label>
                <div class="layui-input-block">
                    <textarea name="brand_profile" placeholder="请输入品牌简介" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">品牌图片</label>
                    &nbsp;&nbsp;
                    <div class="layui-input-inline" style="width: 150px;">
                        <div class="layui-upload">
                            <button type="button" class="layui-btn" id="picUpload">上传图片</button>
                            <div class="layui-upload-list">
                                <input type="hidden" name="brand_pic" autocomplete="off"
                                       class="layui-input">
                                <img class="layui-upload-img" id="pic">
                                <p id="picText"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="checkbox" name="brand_status" lay-skin="switch" lay-text="ON|OFF" checked>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline" style="width: 50px;">
                    <input type="text" name="brand_sort" value="0" autocomplete="off" class="layui-input">
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

            //普通图片上传
            var myToken = $('input[name=_token]').val();
            var uploadInst = upload.render({
                elem: '#picUpload'
                , url: '{{url('admins/uploader/pic_upload')}}'
                , data: {"_token": myToken}
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#pic').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {

                    if (res.code > 0) {  //如果上传失败
                        return layer.msg('上传失败');
                    } else {   //上传成功
                        $('input[name=brand_pic]').val(res.path);
                    }

                }
                , error: function () {
                    //演示失败状态，并实现重传
                    var picText = $('#picText');
                    picText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs pic-reload">重试</a>');
                    picText.find('.pic-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });

            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                if(data.field.brand_status == "on") {
                    data.field.brand_status = "1";
                } else {
                    data.field.brand_status = "0";
                }
                $.ajax({
                    url:"{{url('admins/brand')}}",
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
