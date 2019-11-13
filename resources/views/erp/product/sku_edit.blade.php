@extends('erp.father.father')
@section('content')
    <div class="layui-fluid iframe_scroll">
        <div class="layui-card">
            <div class="layui-card-header">表单组合</div>
            <div class="layui-card-body" style="padding: 15px;">
                <form class="layui-form layui-form-pane" action="">
                    {{csrf_field()}}
                    <hr class="layui-bg-gray">
                    <div class="layui-form-item">
                        <label class="layui-form-label">产品名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" value="{{$data->product_name}}" autocomplete="off" class="layui-input" disabled>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">英文名称</label>
                        <div class="layui-input-inline" style="width: 600px;">
                            <input type="text" value="{{$data->product_english}}" autocomplete="off" class="layui-input" disabled>
                        </div>
                    </div>
                    <hr class="layui-bg-gray">
                    @if($attr)
                    <div id="view"></div>
                    <!-- sku start-->
                        <link rel="stylesheet" href="/admin/css/sku_style.css" />
                        <fieldset class="layui-elem-field site-demo-button" style="padding:20px;">
                            <legend>规格属性</legend>
                            @foreach ($attr as $value)
                            <input type="hidden" name="sp_val[{{$value->id}}][attr_name]" value="{{$value->attribute->attr_name}}"/>
                            <input type="hidden" name="sp_val[{{$value->id}}][attr_english]" value="{{$value->attribute->attr_english}}"/>
                            <div class="layui-form-item">
                                <ul class="SKU_TYPE">
                                    <li class="layui-form-label" is_required='1' sku-type-name="{{$value->attribute->attr_name}}"><em>*</em> {{$value->attribute->attr_name}}：</li>
                                </ul>
                                <ul>
                                    @foreach($value->attribute_value as $k=>$v)
                                            <li><label><input type="checkbox" propid='{{$value->id}}' name="sp_val[{{$value->id}}][attr_value][{{ $v->id }}]" class="sku_value" propvalid='{{ $v->id }}' value="{{ $v->attr_value_name }}" lay-ignore />{{ $v->attr_value_name }}</label></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="clear"></div>
                            @endforeach

                            <li style="display: none;" id="onlySkuValCloneModel">
                                <input type="checkbox" class="model_sku_val" propvalid='' value="" />
                                <input type="text" class="cusSkuValInput" />
                                <a href="javascript:void(0);" class="delCusSkuVal">删除</a>
                            </li>
                            <div class="clear"></div>
                            <div id="skuTable"></div>

                            <div class="clear"></div>

                        </fieldset>

                    <script type="text/javascript" src="/admin/js/getSetSkuVals.js"></script>
                    @endif

                    <!-- sku end-->
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
                        ,table = layui.table;
                    var $ = layui.jquery;

                    $(document).ready(function () {
                        getAlreadySetSkuVals();
                    });


                    var alreadySetSkuVals = {};//已经设置的SKU值数据

                    //sku属性发生改变时,进行表格创建
                    $(document).on("change",'.sku_value',function(){
                        getAlreadySetSkuVals();//获取已经设置的SKU值
                        //console.log(alreadySetSkuVals);
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
                                var alreadySetSkuImg = "/admin/image/img.png";//已经设置的图片
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
                                    ''+currRowDoms+'<td><input type="text" class="setting_sku_cost_price" name="sku['+i+'][sku_cost_price]" value="'+alreadySetSkuCostPrice+'"/></td><td><input type="text" class="setting_sku_price" name="sku['+i+'][sku_price]" value="'+alreadySetSkuPrice+'"/></td><td><input type="text" name="sku['+i+'][sku_num]" class="setting_sku_stock" value="'+alreadySetSkuStock+'"/></td><td><div class="layui-upload sku_img"><div class="layui-upload-list test1"><img class="layui-upload-img demo1" src="'+alreadySetSkuImg+'"><p id="demoText"></p></div></div><input type="hidden" name="sku['+i+'][sku_image]" class="setting_sku_stocksetting_sku_img" value="'+alreadySetSkuImg+'"/></td></tr>';
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
                        alreadySetSkuVals = '{{ $goods_sku }}';
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








                    //监听提交
                    form.on('submit(form)', function (data) {
                        //console.log(table.cache);
                        data.field.table=table.cache;
                        //console.log(dataObj);
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
