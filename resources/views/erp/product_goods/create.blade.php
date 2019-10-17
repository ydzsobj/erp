@extends('erp.father.father')
@section('content')
    <div class="layui-fluid iframe_scroll">
        <div class="layui-card">
            <div class="layui-card-header">表单组合</div>
            <div class="layui-card-body" style="padding: 15px;">
                <form class="layui-form layui-form-pane" action="">
                    {{csrf_field()}}
                    <div class="layui-form-item">
                        <label class="layui-form-label">分类名称</label>
                        <div class="layui-input-inline">
                            <select name="category_id" lay-filter="category">
                                @foreach($category as $value)
                                    <option value="{{$value->id}}">{{$value->category_name}}</option>
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
                                <input type="text" name="product_cost_price" placeholder="￥" autocomplete="off"
                                       class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">销售价</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="product_price" placeholder="￥" autocomplete="off"
                                       class="layui-input">
                            </div>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品货号</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <select name="brand_id">
                                <option value="0">请选择品牌</option>
                                {{--@foreach($category as $value)
                                    <option value="{{$value->id}}">{{$value->category_name}}</option>
                                @endforeach--}}
                            </select>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品货号</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <input type="text" name="product_spu" autocomplete="off" class="layui-input" maxlength="6">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品条形码</label>
                        <div class="layui-input-inline" style="width: 300px;">
                            <input type="text" name="product_barcode" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
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
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="supplier_id" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">辅供应商</label>
                            <div class="layui-input-inline" style="width: 150px;">
                                <input type="text" name="supplier_bid" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <script id="info" type="text/html">
                        <hr class="layui-bg-gray">
                        @{{#  layui.each(d, function(index, i){ }}
                        <div class="layui-form-item @{{i.attr_english}}">
                            <label class="layui-form-label">@{{i.attr_name}}</label>
                            <div class="layui-input-block"  >
                                @{{#  layui.each(i.attrValue, function(index_1, item){ }}
                                <input type="checkbox" name="sp_val[@{{i.id}}][@{{ item.attr_value_id }}]" value="@{{ item.attr_value_name }}" value_id="@{{ item.attr_value_id }}" attr-id="@{{index}}" lay-filter="@{{i.attr_english}}" title="@{{ item.attr_value_name }}" lay-skin="primary" >
                                @{{#  }); }}
                            </div>
                        </div>
                        @{{#  }); }}

                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">普通文本域</label>
                            <div class="layui-input-block">
                                <table class="layui-hide" id="table-cellEdit" lay-filter="table-cellEdit"></table>
                            </div>
                        </div>
                    </script>
                    <div id="view">

                    </div>

                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品图片</label>
                            &nbsp;&nbsp;
                            <div class="layui-input-inline" style="width: 150px;">
                                <div class="layui-upload">
                                    <button type="button" class="layui-btn" id="picUpload">上传图片</button>
                                    <div class="layui-upload-list">
                                        <input type="hidden" name="product_image" autocomplete="off"
                                               class="layui-input">
                                        <img class="layui-upload-img" id="pic">
                                        <p id="picText"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">产品详情</label>
                        <div class="layui-input-block">
                            <textarea id="content" name="product_content" style="display: none;"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">产品推荐</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="product_commend" value="0" title="否" checked="">
                                <input type="radio" name="product_commend" value="1" title="是">
                            </div>
                            <div class="layui-form-mid"></div>
                            <label class="layui-form-label">产品状态</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="product_state" value="0" title="下架">
                                <input type="radio" name="product_state" value="1" title="正常" checked="">
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
                //info
                layui.config({
                    base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
                }).use(['form', 'upload', 'layedit','table','laytpl'], function () {
                    var form = layui.form
                        ,upload = layui.upload
                        ,table = layui.table
                        ,laytpl = layui.laytpl;
                    var layedit = layui.layedit;
                    var $ = layui.jquery;
                    var options=[]
                        ,title=[]
                        ,dataObj=[]
                        ,data_1=[]
                        ,data_2=[]
                        ,data_3=[]
                        ,data_4=[];

                    Array.prototype.remove = function(val) {
                        var index = this.indexOf(val);
                        if (index > -1) {
                            this.splice(index, 1);
                        }
                    };

                    function addtable(data,count){
                        console.log(title);
                        if(data.elem.checked == true){
                            dataObj=[];
                            console.log($(data.elem).attr('attr-id'));
                            data.value_id = $(data.elem).attr('value_id');
                            if($(data.elem).attr('attr-id')==0){
                                data_1.push(data)
                            }else if($(data.elem).attr('attr-id')==1){
                                data_2.push(data)
                            }else if($(data.elem).attr('attr-id')==2){
                                data_3.push(data)
                            }else if($(data.elem).attr('attr-id')==3){
                                data_4.push(data)
                            }
                            var title_1=title[0],
                                title_2=title[1],
                                title_3=title[2],
                                title_4=title[3];
                            data_1.forEach((item, index) => {
                                console.log(item);
                                if(count==1){
                                    dataObj.push({ title_1:item.value,title_1_id:item.value_id, 'sku_cost_price': '0','sku_price': '0','sku_num': '0','sku_barcode': ''})
                                }
                                if(data_2.length!=0 ){
                                    data_2.forEach((item_1, index) => {
                                        if(count == 2){
                                            dataObj.push({title_1:item.value,title_2:item_1.value,title_1_id:item.value_id,title_2_id:item_1.value_id, 'sku_cost_price': '0','sku_price': '0','sku_num': '0','sku_barcode': ''})
                                        }
                                        if(data_3.length!=0){
                                            data_3.forEach((item_2, index) => {
                                                if(count == 3){
                                                    dataObj.push({ title_1 :item.value,title_2:item_1.value,title_3:item_2.value,title_1_id:item.value_id,title_2_id:item_1.value_id,title_3_id:item_2.value_id,  'sku_cost_price': '0','sku_price': '0','sku_num': '0','sku_barcode': ''})
                                                }
                                                if(data_3.length!=0){
                                                    data_4.forEach((item_3, index) => {
                                                        if(count == 4){
                                                            dataObj.push({title_1:item.value,title_2:item_1.value,title_3:item_2.value,title_4:item_3.value,title_1_id:item.value_id,title_2_id:item_1.value_id,title_3_id:item_2.value_id,title_4_id:item_3.value_id,  'sku_cost_price': '0','sku_price': '0','sku_num': '0','sku_barcode': ''})
                                                        }
                                                    })
                                                }
                                            })
                                        }
                                    })
                                }
                            })
                        }else{
                            if($(data.elem).attr('attr-id')==0){
                                data_1.remove(data.value)
                                dataObj=dataObj.filter(function(j, i){
                                    return j.title_1 !=data.value
                                })
                            }else if($(data.elem).attr('attr-id')==1){
                                data_2.remove(data.value)
                                dataObj=dataObj.filter(function(j, i){
                                    return j.title_2 !=data.value
                                })
                            }else if($(data.elem).attr('attr-id')==2){
                                data_3.remove(data.value)
                                dataObj=dataObj.filter(function(j, i){
                                    return j.title_3 !=data.value
                                })
                            }else if($(data.elem).attr('attr-id')==3){
                                data_4.remove(data.value)
                                dataObj=dataObj.filter(function(j, i){
                                    return j.title_4!=data.value
                                })
                            }
                        }
                        console.log(dataObj);
                        table.render({
                            elem: '#table-cellEdit'
                            ,height: 500
                            ,data: dataObj
                            ,cols: [options]
                            ,limit:dataObj.length
                        })
                    }


                    $('.iframe_scroll').parent().css('overflow', 'visible');
                    var myToken = $('input[name=_token]').val();

                    //普通图片上传
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
                                $('input[name=product_image]').val(res.path);
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

                    //编辑器上传
                    layedit.set({
                        height: '300px',
                        uploadImage: {
                            url: '{{url('admins/uploader/pic_upload')}}'
                            , type: 'post'
                            , data: {"_token": myToken}
                        }
                    });
                    layedit.build('content'); //建立编辑器


                    //监听select
                    form.on('select(category)',function (data) {
                        $.ajax({
                            url: "{{url('admins/attribute/get_attr')}}",
                            type: 'get',
                            data: {'category_id':data.value},
                            datatype: 'json',
                            success: function (msg) {
                                console.log(msg);
                                var getTpl = info.innerHTML
                                    ,view = $('#view');
                                    view.empty(),
                                    options=[{field:'sku_cost_price', title:'成本价', edit: 'text', minWidth: 100},{field:'sku_price', title:'销售价', edit: 'text', minWidth: 100},{field:'sku_num', title:'库存', edit: 'text', minWidth: 100},{field:'sku_barcode', title:'条形码', edit: 'text', minWidth: 200}];
                                    title=[];
                                    dataObj=[];
                                    data_1=[];
                                    data_2=[];
                                    data_3=[];
                                    data_4=[];
                                if(msg!=''){
                                    laytpl(getTpl).render(msg, function(html){
                                        view.append(html)
                                    });
                                    form.render();
                                    msg.forEach(function(item,i){
                                        title.push(item.attr_english);
                                        var a=i+1;
                                        options.unshift({field:'title_'+a, title:item.attr_name, width:80});
                                        // console.log(i)
                                        form.on('checkbox('+item.attr_english+')', function(data){
                                            addtable(data,msg.length)
                                        });
                                    })

                                }




                            },
                            error: function (XmlHttpRequest, textStatus, errorThrown) {
                                layer.msg('error!', {icon: 2, time: 2000});
                            }
                        });
                        return false;
                        
                    });

                    //监听提交
                    form.on('submit(form)', function (data) {
                        //console.log(table.cache);
                        data.field.table=table.cache;
                        console.log(dataObj);
                        //layer.msg(JSON.stringify(data.field));
                        $.ajax({
                            url: "{{url('admins/product')}}",
                            type: 'post',
                            data: data.field,
                            datatype: 'json',
                            success: function (msg) {
                                if (msg == '0') {
                                    layer.msg('添加成功！', {icon: 1, time: 2000}, function () {
                                        var index = parent.layer.getFrameIndex(window.name);
                                        //刷新
                                        parent.window.location = parent.window.location;
                                        parent.layer.close(index);
                                    });
                                } else {
                                    layer.msg('添加失败！', {icon: 2, time: 2000});
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
