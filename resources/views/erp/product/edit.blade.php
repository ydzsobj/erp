@extends('erp.father.father')
<style>
    .sku_img{
        position:relative;
        width:96px;
        height:60px;
    }
    .sku_img .layui-btn {
        height:60px
    }
    .sku_img .layui-upload-list{
        width:96px;
        height:60px;
        position:absolute;
        left:0;
        top:0;
        margin:0
    }
    .demo1{
        /* display:none; */
        width:100%;
        height:100%
    }
    .demo1 img{
        width:100%
    }
</style>
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

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">产品详情</label>
                        <div class="layui-input-block">
                            <textarea id="content" name="product_content" style="display: none;">{{$data->product_content}}</textarea>
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
                                <input type="radio" name="product_status" value="0" title="下架" @if($data->product_status == '0') checked @endif>
                                <input type="radio" name="product_status" value="1" title="正常"  @if($data->product_status == '1') checked @endif>
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
                    var index = layedit.build('content'); //建立编辑器

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


                    $('.imgUpload').each(function(i,elem){ add(elem)});


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
                        if(data.field.product_image==''||data.field.product_image==null){
                            layer.msg('产品图片错误，请重新传图！');
                            return false;
                        }
                        data.field.product_content = layedit.getContent(index);
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
                    function add(elem){
                        var uploadInst = upload.render({
                            elem: elem
                            ,url: '/upload/'+elem
                            ,before: function(obj){
                                //预读本地文件示例，不支持ie8
                                obj.preview(function(index, file, result){
                                    $(elem).find("img").attr('src', result); //图片链接（base64）
                                    $(elem).parent().next().val(33333333333333)
                                });
                            }
                            ,done: function(res){
                                //如果上传失败
                                console.log($(elem).parent().next())
                                if(res.code > 0){
                                    return layer.msg('上传失败');
                                }
                                $(elem).parent().next().val(res)

                                //上传成功
                            }
                            ,error: function(){
                                //演示失败状态，并实现重传
                                // var demoText = $('#demoText');
                                // demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                                // demoText.find('.demo-reload').on('click', function(){
                                //   uploadInst.upload();
                                // });
                            }
                        });
                    }
                    // $('.test1').each(function(i,elem){ add(elem)})
                    var alreadySetSkuVals = {};//已经设置的SKU值数据

                    // $(function(){
                    //sku属性发生改变时,进行表格创建
                    $(document).on("change",'.sku_value',function(){
                        //$(document).ready(function(){
                        getAlreadySetSkuVals();//获取已经设置的SKU值
                        console.log(alreadySetSkuVals);
                        var b = true;
                        var skuTypeArr =  [];//存放SKU类型的数组
                        var totalRow = 1;//总行数
                        //获取元素类型
                        $(".SKU_TYPE").each(function(){
                            //SKU类型节点
                            var skuTypeNode = $(this).children("li");
                            var skuTypeObj = {};//sku类型对象
                            //SKU属性类型标题
                            skuTypeObj.skuTypeTitle = $(skuTypeNode).attr("sku-type-name");
                            //SKU属性类型主键
                            var propid = $(skuTypeNode).attr("propid");
                            skuTypeObj.skuTypeKey = propid;
                            //是否是必选SKU 0：不是；1：是；
                            var is_required = $(skuTypeNode).attr("is_required");
                            skuValueArr = [];//存放SKU值得数组
                            //SKU相对应的节点
                            var skuValNode = $(this).next();
                            //获取SKU值
                            var skuValCheckBoxs = $(skuValNode).find("input[type='checkbox'][class*='sku_value']");
                            var checkedNodeLen = 0 ;//选中的SKU节点的个数
                            $(skuValCheckBoxs).each(function(){
                                if($(this).is(":checked")){
                                    var skuValObj = {};//SKU值对象
                                    skuValObj.skuValueTitle = $(this).val();//SKU值名称
                                    skuValObj.skuValueId = $(this).attr("propvalid");//SKU值主键
                                    skuValObj.skuPropId = $(this).attr("propid");//SKU类型主键
                                    skuValueArr.push(skuValObj);
                                    checkedNodeLen ++ ;
                                }
                            });
                            if(is_required && "1" == is_required){//必选sku
                                if(checkedNodeLen <= 0){//有必选的SKU仍然没有选中
                                    b = false;
                                    return false;//直接返回
                                }
                            }
                            if(skuValueArr && skuValueArr.length > 0){
                                totalRow = totalRow * skuValueArr.length;
                                skuTypeObj.skuValues = skuValueArr;//sku值数组
                                skuTypeObj.skuValueLen = skuValueArr.length;//sku值长度
                                skuTypeArr.push(skuTypeObj);//保存进数组中
                            }
                        });
                        var SKUTableDom = "";//sku表格数据
                        //开始创建行
                        if(b){//必选的SKU属性已经都选中了

//			//调整顺序(少的在前面,多的在后面)
//			skuTypeArr.sort(function(skuType1,skuType2){
//				return (skuType1.skuValueLen - skuType2.skuValueLen)
//			});
//
                            SKUTableDom += "<table class='skuTable'><tr>";
                            //创建表头
                            for(var t = 0 ; t < skuTypeArr.length ; t ++){
                                SKUTableDom += '<th>'+skuTypeArr[t].skuTypeTitle+'</th>';
                            }
                            SKUTableDom += '<th>成本价格</th><th>销售价格</th><th>库存</th><th>图片</th>';
                            SKUTableDom += "</tr>";
                            //循环处理表体
                            for(var i = 0 ; i < totalRow ; i ++){//总共需要创建多少行
                                var currRowDoms = "";
                                var rowCount = 1;//记录行数
                                var propvalidArr = [];//记录SKU值主键
                                var propIdArr = [];//属性类型主键
                                var propvalnameArr = [];//记录SKU值标题
                                var propNameArr = [];//属性类型标题
                                for(var j = 0 ; j < skuTypeArr.length ; j ++){//sku列
                                    var skuValues = skuTypeArr[j].skuValues;//SKU值数组
                                    var skuValueLen = skuValues.length;//sku值长度
                                    rowCount = (rowCount * skuValueLen);//目前的生成的总行数
                                    var anInterBankNum = (totalRow / rowCount);//跨行数
                                    var point = ((i / anInterBankNum) % skuValueLen);
                                    propNameArr.push(skuTypeArr[j].skuTypeTitle);
                                    if(0  == (i % anInterBankNum)){//需要创建td
                                        currRowDoms += '<td rowspan='+anInterBankNum+'>'+skuValues[point].skuValueTitle+'</td>';
                                        propvalidArr.push(skuValues[point].skuValueId);
                                        propIdArr.push(skuValues[point].skuPropId);
                                        propvalnameArr.push(skuValues[point].skuValueTitle);
                                    }else{
                                        //当前单元格为跨行
                                        propvalidArr.push(skuValues[parseInt(point)].skuValueId);
                                        propIdArr.push(skuValues[parseInt(point)].skuPropId);
                                        propvalnameArr.push(skuValues[parseInt(point)].skuValueTitle);
                                    }
                                }
//
//				//进行排序(主键小的在前,大的在后),注意:适用于数值类型的主键
//				propvalidArr.sort(function(provids1,propvids2){
//					return (provids1 - propvids2)
//				});

                                var propvalids = propvalidArr.toString();
                                var alreadySetSkuPrice = "0";//已经设置的SKU价格
                                var alreadySetSkuCostPrice = "0";//已经设置的SKU价格
                                var alreadySetSkuStock = "0";//已经设置的SKU库存
                                var alreadySetSkuImg = "";//已经设置的图片
                                //赋值
                                if(alreadySetSkuVals){
                                    var currGroupSkuVal = alreadySetSkuVals[propvalids];//当前这组SKU值
                                    if(currGroupSkuVal){
                                        alreadySetSkuPrice = currGroupSkuVal.skuPrice;
                                        alreadySetSkuCostPrice = currGroupSkuVal.skuCostPrice;
                                        alreadySetSkuStock = currGroupSkuVal.skuStock;
                                        alreadySetSkuImg = currGroupSkuVal.skuImg;
                                    }
                                }
                                //console.log(propvalids);
                                SKUTableDom += '<tr propvalids=\''+propvalids+'\' propids=\''+propIdArr.toString()+'\' propvalnames=\''+propvalnameArr.join(";")+'\'  propnames=\''+propNameArr.join(";")+'\' class="sku_table_tr">' +
                                    '<input type="hidden" name="sku['+i+'][propids]" value="'+propIdArr.toString()+'"/><input type="hidden" name="sku['+i+'][propnames]" value="'+propNameArr.join(";")+'"/><input type="hidden" name="sku['+i+'][propvalids]" value="'+propvalids+'"/><input type="hidden" name="sku['+i+'][propvalnames]" value="'+propvalnameArr.join(";")+'"/>'+
                                    '<input type="hidden" name="sku['+i+'][sku_attr_id]" value="'+skuValues[point].skuPropId+'"/><input type="hidden" name="sku['+i+'][sku_attr_name]" value=""/><input type="hidden" name="sku['+i+'][sku_attr_value_id]" value="'+skuValues[point].skuValueId+'"/><input type="hidden" name="sku['+i+'][sku_attr_value_name]" value="'+skuValues[point].skuValueTitle+'"/>'+
                                    ''+currRowDoms+'<td><input type="text" class="setting_sku_cost_price" name="sku['+i+'][sku_cost_price]" value="'+alreadySetSkuCostPrice+'"/></td><td><input type="text" class="setting_sku_price" name="sku['+i+'][sku_price]" value="'+alreadySetSkuPrice+'"/></td><td><input type="text" name="sku['+i+'][sku_num]" class="setting_sku_stock" value="'+alreadySetSkuStock+'"/></td><td><div class="layui-upload sku_img"><button type="button" class="layui-btn " class="test1">上传图片</button><div class="layui-upload-list test1"><img class="layui-upload-img demo1"><p id="demoText"></p></div></div><input type="hidden" name="sku['+i+'][sku_num]" class="setting_sku_stocksetting_sku_img" value="'+alreadySetSkuImg+'"/></td></tr>';
                            }
                            SKUTableDom += "</table>";
                        }
                        $("#skuTable").html(SKUTableDom);
                        $('.test1').each(function(i,elem){ add(elem)})
                    });
// });

                    /**
                     * 获取已经设置的SKU值
                     */
                    function getAlreadySetSkuVals(){
                        alreadySetSkuVals = {'1,4':{'skuCostPrice': "123",'skuPrice': "123",'skuStock': "123",'skuImg':"456"},'1,6':{'skuCostPrice': "222",'skuPrice': "12223",'skuStock': "1223",'skuImg':"456"}};
                        //获取设置的SKU属性值
                        $("tr[class*='sku_table_tr']").each(function(){
                            var skuCostPrice = $(this).find("input[type='text'][class*='setting_sku_cost_price']").val();//SKU价格
                            var skuPrice = $(this).find("input[type='text'][class*='setting_sku_price']").val();//SKU价格
                            var skuStock = $(this).find("input[type='text'][class*='setting_sku_stock']").val();//SKU库存
                            var skuImg = $(this).find("input[type='hidden'][class*='setting_sku_img']").val();//SKU图
                            if(skuPrice || skuStock){//已经设置了全部或部分值
                                var propvalids = $(this).attr("propvalids");//SKU值主键集合
                                alreadySetSkuVals[propvalids] = {
                                    "skuCostPrice" : skuCostPrice,
                                    "skuPrice" : skuPrice,
                                    "skuStock" : skuStock,
                                    "skuImg" : skuImg
                                }
                            }
                        });
                    }

                });

            </script>
            <script type="text/javascript" src="/admin/js/jquery.min.js"></script>
            <!-- <script type="text/javascript" src="/admin/js/createSkuTable.js"></script> -->
            <script type="text/javascript" src="/admin/js/customSku.js"></script>
            <script type="text/javascript" src="/admin/js/layer.js"></script>

@endsection
