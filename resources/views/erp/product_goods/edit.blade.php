@extends('erp.father.father')
@section('content')
    <div class="layui-fluid iframe_scroll">
        <div class="layui-card">
            <div class="layui-card-header">编辑信息</div>
            <div class="layui-card-body" style="padding: 15px;">
                <form class="layui-form layui-form-pane" action="" lay-filter="formData">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <label class="layui-form-label">SKU名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" name="sku_name" lay-verify="required" lay-reqtext="SKU名称不能为空"
                                   placeholder="请输入SKU名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">英文名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" name="sku_english" placeholder="请输入英文名称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">成本价</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="sku_cost_price" placeholder="￥" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">销售价</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="sku_price" placeholder="￥" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">SKU条形码</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <input type="text" name="sku_barcode" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">库存数量</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="sku_num" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">库存报警值</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="sku_num_alarm" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品图片</label>
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="picUpload">上传图片</button>
                                <div class="layui-upload-list">
                                    <input type="hidden" name="sku_image" autocomplete="off" class="layui-input">
                                    <img class="layui-upload-img" id="pic" src="{{$data->sku_image}}">
                                    <p id="picText"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">SKU状态</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="sku_state" value="0" title="下架" @if($data->sku_state == '0') checked @endif>
                                <input type="radio" name="sku_state" value="1" title="正常"  @if($data->sku_state == '1') checked @endif>
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="form">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        @endsection
        @section('js')
            <script>
                //Demo
                layui.config({
                    base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
                }).use(['form', 'upload', 'layedit'], function () {
                    var form = layui.form
                        , upload = layui.upload;
                    var layedit = layui.layedit;
                    var $ = layui.jquery;

                    $('.iframe_scroll').parent().css('overflow', 'visible');

                    var myToken = $('input[name=_token]').val();
                    //普通图片上传
                    var uploadInst = upload.render({
                        elem: '#picUpload'
                        , url: '{{url('admins/uploader/pic_upload')}}'
                        , data:{"_token":myToken}
                        , before: function (obj) {
                            //预读本地文件示例，不支持ie8
                            obj.preview(function (index, file, result) {
                                $('#pic').attr('src', result); //图片链接（base64）
                            });
                        }
                        , done: function (res) {

                            if (res.code > 0) {  //如果上传失败
                                return layer.msg('上传失败');
                            }else{   //上传成功
                                $('input[name=sku_image]').val(res.path);
                            }

                        }
                        , error: function () {
                            //演示失败状态，并实现重传
                            var picText = $('#picText');
                            picText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                            picText.find('.demo-reload').on('click', function () {
                                uploadInst.upload();
                            });
                        }
                    });

                    layedit.set({
                        height: '300px',
                        uploadImage: {
                            url: '/upload/',
                            type: 'post'
                        }
                    });

                    layedit.build('content'); //建立编辑器


                    //表单初始赋值
                    form.val('formData', {
                        "sku_name": "{{$data->sku_name}}"
                        ,"sku_english": "{{$data->sku_english}}"
                        ,"sku_cost_price": "{{$data->sku_cost_price}}"
                        ,"sku_price": "{{$data->sku_price}}"
                        ,"sku_barcode": "{{$data->sku_barcode}}"
                        ,"sku_num": "{{$data->sku_num}}"
                        ,"sku_num_alarm": "{{$data->sku_num_alarm}}"
                        ,"sku_image": "{{$data->sku_image}}"

                    });

                    //监听提交
                    form.on('submit(form)', function (data) {
                        //layer.msg(JSON.stringify(data.field));
                        $.ajax({
                            url: "{{url('admins/product_goods/'.$data->id)}}",
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
                            error: function (XmlHttpRequest, textStatus, errorThrown) {
                                layer.msg('error!', {icon: 2, time: 2000});
                            }
                        });
                        return false;
                    });
                });
            </script>
@endsection
