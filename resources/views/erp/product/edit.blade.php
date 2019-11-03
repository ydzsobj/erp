@extends('erp.father.father')
@section('content')
    <div class="layui-fluid iframe_scroll">
        <div class="layui-card">
            <div class="layui-card-header">编辑信息</div>
            <div class="layui-card-body" style="padding: 15px;">
                <form class="layui-form layui-form-pane" action="" lay-filter="formData">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <label class="layui-form-label">分类名称</label>
                        <div class="layui-input-inline">
                            <select name="category_id" disabled>
                                @foreach($category as $value)
                                    <option value="{{$value->id}}" @if($value->id == $data->category_id) selected @endif>{{$value->category_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" name="product_name" lay-verify="required" lay-reqtext="产品名称不能为空"
                                   placeholder="请输入产品名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">英文名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" name="product_english" placeholder="请输入英文名称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">成本价</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="product_cost_price" placeholder="￥" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">销售价</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="product_price" placeholder="￥" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品品牌</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="brand_id">
                                <option value="0">请选择品牌</option>
                                @foreach($brand as $value)
                                    <option value="{{$value->id}}" @if($value->id==$data->brand_id) selected @endif>{{$value->brand_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">

                    <div class="layui-form-item">
                        <label class="layui-form-label">产品条形码</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <input type="text" name="product_barcode" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品尺寸</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="product_size" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">重量或体积</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="product_weight" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">主供应商</label>
                            <div class="layui-input-inline" style="width: 300px;">
                                <select name="supplier_id">
                                    <option value="0">请选择主供应商</option>
                                    @foreach($supplier as $value)
                                        <option value="{{$value->id}}" @if($value->id==$data->supplier_id) selected @endif>{{$value->supplier_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">辅供应商</label>
                            <div class="layui-input-inline" style="width: 300px;">
                                <select name="supplier_bid">
                                    <option value="0">请选择辅供应商</option>
                                    @foreach($supplier as $value)
                                        <option value="{{$value->id}}" @if($value->id==$data->supplier_bid) selected @endif>{{$value->supplier_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">主供应链接</label>
                            <div class="layui-input-inline" style="width: 300px;">
                                <input type="text" name="supplier_url" placeholder="请输入主供应商链接" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">辅供应链接</label>
                            <div class="layui-input-inline" style="width: 300px;">
                                <input type="text" name="supplier_burl" placeholder="请输入辅供应商链接" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div id="view"></div>
                    <!-- sku start-->
                    <script id="info" type="text/html">
                        <link rel="stylesheet" href="/admin/css/sku_style.css" />
                        <fieldset class="layui-elem-field site-demo-button" style="padding:20px;">
                            <legend>规格属性</legend>
                            @{{#  layui.each(d, function(index, i){ }}
                            <input type="hidden" name="sp_val[@{{i.id}}][attr_name]" value="@{{i.attr_name}}"/>
                            <input type="hidden" name="sp_val[@{{i.id}}][attr_english]" value="@{{i.attr_english}}"/>
                            <div class="layui-form-item">
                                <ul class="SKU_TYPE">
                                    <li class="layui-form-label" is_required='1' sku-type-name="@{{i.attr_name}}"><em>*</em> @{{i.attr_name}}：</li>
                                </ul>
                                <ul>
                                    @{{#  layui.each(i.attributes, function(index_1, item){ }}
                                    <li><label><input type="checkbox" propid='@{{i.id}}' name="sp_val[@{{i.id}}][attr_value][@{{ item.id }}]" class="sku_value" propvalid='@{{ item.id }}' value="@{{ item.attr_value_name }}" lay-ignore/>@{{ item.attr_value_name }}</label></li>
                                    @{{#  }); }}
                                </ul>
                            </div>
                            <div class="clear"></div>
                            @{{#  }); }}

                            <li style="display: none;" id="onlySkuValCloneModel">
                                <input type="checkbox" class="model_sku_val" propvalid='' value="" />
                                <input type="text" class="cusSkuValInput" />
                                <a href="javascript:void(0);" class="delCusSkuVal">删除</a>
                            </li>
                            <div class="clear"></div>
                            <div id="skuTable"></div>

                            <div class="clear"></div>

                        </fieldset>

                    </script>
                    <script type="text/javascript" src="/admin/js/getSetSkuVals.js"></script>

                    <!-- sku end-->
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品图片</label>
                            <div class="layui-upload">
                                <button type="button" class="layui-btn" id="picUpload">上传图片</button>
                                <div class="layui-upload-list">
                                    <input type="hidden" name="product_image" autocomplete="off" class="layui-input">
                                    <img class="layui-upload-img" id="pic" src="{{$data->product_image}}">
                                    <p id="picText"></p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品推荐</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="product_commend" value="0" title="否" @if($data->product_commend == '0') checked @endif >
                                <input type="radio" name="product_commend" value="1" title="是" @if($data->product_commend == '1') checked @endif>
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">产品状态</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="product_state" value="0" title="下架" @if($data->product_state == '0') checked @endif>
                                <input type="radio" name="product_state" value="1" title="正常"  @if($data->product_state == '1') checked @endif>
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
                }).use(['form', 'upload', 'layedit','jquery', 'table', 'laytpl'], function () {
                    var form = layui.form
                        , upload = layui.upload
                        , laytpl = layui.laytpl;
                    var layedit = layui.layedit;
                    var $ = layui.jquery;

                    //表单初始赋值
                    form.val('formData', {
                        "product_name": "{{$data->product_name}}"
                        ,"product_english": "{{$data->product_english}}"
                        ,"product_cost_price": "{{$data->product_cost_price}}"
                        ,"product_price": "{{$data->product_price}}"
                        ,"brand_id": "{{$data->brand_id}}"
                        ,"product_barcode": "{{$data->product_barcode}}"
                        ,"product_size": "{{$data->product_size}}"
                        ,"product_weight": "{{$data->product_weight}}"
                        ,"product_image": "{{$data->product_image}}"
                        ,"supplier_url": "{{$data->supplier_url}}"
                        ,"supplier_burl": "{{$data->supplier_burl}}"
                        ,"product_content": "{{$data->product_content}}"

                    });



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
                                $('input[name=product_image]').val(res.path);
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




                    function add(elem){
                        var id = $(elem).attr('attr_id');console.log(id);
                        var uploadInst = upload.render({
                            elem: elem
                            , url: '{{url('admins/uploader/pic_upload')}}'
                            , data:{"_token":myToken}
                            ,before: function(obj){
                                //预读本地文件示例，不支持ie8
                                obj.preview(function(index, file, result){
                                    $('#pic'+id).attr('src', result); //图片链接（base64）
                                });
                            }
                            ,done: function(res){
                                //如果上传失败
                                if(res.code > 0){
                                    return layer.msg('上传失败');
                                }else {   //上传成功
                                    $('input[name_id=color_image'+id+']').val(res.path);
                                }
                            }
                            ,error: function(){
                                //演示失败状态，并实现重传
                                var picText = $('#picText');
                                picText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs pic-reload">重试</a>');
                                picText.find('.pic-reload').on('click', function () {
                                    uploadInst.upload();
                                });
                            }
                        });
                    }


                    $('.imgUpload').each(function(i,elem){ add(elem)})


                    $('#view').empty();
                    $.ajax({
                        url: "{{url('admins/data/get_attr')}}",
                        type: 'get',
                        data: {'category_id':'15'},
                        datatype: 'json',
                        success: function (msg) {
                            console.log(msg);
                            var getTpl = info.innerHTML
                                ,view = $('#view');
                            if(msg!=''){
                                laytpl(getTpl).render(msg, function(html){
                                    view.append(html)
                                });
                                form.render();
                            }
                        },
                        error: function (XmlHttpRequest, textStatus, errorThrown) {
                            layer.msg('error!', {icon: 2, time: 2000});
                        }
                    });



                    //监听提交
                    form.on('submit(form)', function (data) {
                        //layer.msg(JSON.stringify(data.field));
                        $.ajax({
                            url: "{{url('admins/product/'.$data->id)}}",
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
            <script type="text/javascript" src="/admin/js/jquery.min.js"></script>
            <script type="text/javascript" src="/admin/js/createSkuTable.js"></script>
            <script type="text/javascript" src="/admin/js/customSku.js"></script>
            <script type="text/javascript" src="/admin/js/layer.js"></script>

@endsection
