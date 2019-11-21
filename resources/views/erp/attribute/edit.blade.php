@extends('erp.father.father')
@section('content')
    <style type="text/css">
        /*您可以将下列样式写入自己的样式表中*/
        .childBody{padding: 15px;}

        /*layui 元素样式改写*/
        .layui-btn-sm{line-height: normal; font-size: 12.5px;}
        .layui-table-view .layui-table-body{min-height: 256px;}
        .layui-table-cell .layui-input.layui-unselect{height: 30px; line-height: 30px;}

        /*设置 layui 表格中单元格内容溢出可见样式*/
        .table-overlay .layui-table-view,
        .table-overlay .layui-table-box,
        .table-overlay .layui-table-body{overflow: visible;}
        .table-overlay .layui-table-cell{height: auto; overflow: visible;}

        /*文本对齐方式*/
        .text-center{text-align: center;}
    </style>
    <div class="layui-fluid">
        <form class="layui-form" action=""  lay-filter="formData">
            {{csrf_field()}}
            <div class="layui-form-item">
                <label class="layui-form-label">属性类型</label>
                <div class="layui-input-inline">
                    <select name="type_id" disabled>
                        <option value="0">顶级分类</option>
                        @foreach($type as $value)
                            <option value="{{$value->id}}" @if($value->id==$data->type_id) selected @endif>{{$value->type_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">属性名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="attr_name" lay-verify="required" lay-reqtext="属性名称不能为空"
                           placeholder="请输入属性名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">英文名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="attr_english" lay-verify="required" lay-reqtext="英文名称不能为空"
                           placeholder="请输入英文名称" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid" style="color: #ff0000">* 必填</div>
            </div>
            <div id="toolbar">
                <div>
                    <button type="button" class="layui-btn layui-btn-sm" data-type="addRow" title="添加一行">
                        <i class="layui-icon layui-icon-add-1"></i> 添加一行
                    </button>
                </div>
            </div>
            <div id="tableRes" class="table-overlay">
                <table id="dataTable" lay-filter="dataTable" class="layui-hide"></table>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">属性展示</label>
                <div class="layui-input-inline">
                    <div class="layui-col-md12">
                        <input type="checkbox" name="attr_status" lay-skin="switch" lay-text="ON|OFF">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" data-type="save" lay-submit="" lay-filter="form">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>

    </div>


@endsection
@section('js')
    <script>

        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).use(['form', 'table', 'layer'], function(){
            var form = layui.form,
                table = layui.table,
                layer = layui.layer;
            var $=layui.jquery;





            //数据表格实例化
            var tbWidth = $("#tableRes").width();
            var layTableId = "AttrValue";
            var tableIns = table.render({
                elem: '#dataTable',
                id: layTableId,
                data: {},
                width: tbWidth,
                page: true //开启分页
                ,count: 200
                ,limit: 200
                ,limits: [10,20,30,50,100,200],
                loading: true,
                even: false, //不开启隔行背景
                cols: [[
                    {title: '序号', type: 'numbers'},
                    {field: 'id', title: 'ID', width:80,sort: true},
                    {field: 'attr_value_name', title: '名称（name）', edit: 'text'},
                    {field: 'attr_value_english', title: '英文名（english）', edit: 'text'},
                    {field: 'attr_value_code', title: '编码（code）', edit: 'text',width:100},
                    {field: 'attr_value_sort', title: '排序（sort）', edit: 'text',width:100},
                    {field: 'state', title: '是否启用（state）', event: 'state', templet: function(d){
                            var html = ['<input type="checkbox" name="attr_value_status" lay-skin="switch" lay-text="是|否"'];
                            html.push(d.attr_value_status > 0 ? ' checked' : '');
                            html.push('>');
                            return html.join('');
                    }},
                    {field: 'tempId', title: '操作', templet: function(d){
                            return '<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" lay-id="'+ d.id +'"><i class="layui-icon layui-icon-delete"></i>移除</a>';
                    }}
                ]],
                done: function(res, curr, count){

                }
            });

            $.ajax({
                type:'get',
                url: "{{url('api/attribute/get_attr_value')}}/{{$data->id}}", //数据接口
                data:{},
                dataType:'json',
                success:function (result) {
                    tableIns.reload({
                        data : result.data
                    });
                }
            });


            //定义事件集合
            var active = {
                addRow: function(){	//添加一行
                    var oldData = table.cache[layTableId];
                    console.log(oldData);
                    var newRow = {tempId: new Date().valueOf(),id:'', attr_id: '{{$data->id}}', type_id: '{{$data->type_id}}', attr_value_name: '',attr_value_english: '',attr_value_code: '', attr_value_status: 0};
                    oldData.push(newRow);
                    tableIns.reload({
                        data : oldData
                    });
                },
                updateRow: function(obj){
                    var oldData = table.cache[layTableId];
                    console.log(oldData);
                    for(var i=0, row; i < oldData.length; i++){
                        row = oldData[i];
                        if(row.tempId == obj.tempId){
                            $.extend(oldData[i], obj);
                            return;
                        }
                    }
                    tableIns.reload({
                        data : oldData
                    });
                },
                removeEmptyTableCache: function(){
                    var oldData = table.cache[layTableId];
                    for(var i=0, row; i < oldData.length; i++){
                        row = oldData[i];
                        if(!row.type_id){
                            oldData.splice(i, 1);    //删除一项
                        }
                        continue;
                    }
                    tableIns.reload({
                        data : oldData
                    });
                },

            };

            //激活事件
            var activeByType = function (type, arg) {
                if(arguments.length === 2){
                    active[type] ? active[type].call(this, arg) : '';
                }else{
                    active[type] ? active[type].call(this) : '';
                }
            }

            //注册按钮事件
            $('.layui-btn[data-type]').on('click', function () {
                var type = $(this).data('type');
                activeByType(type);
            });


            //列事件
            table.on('edit(dataTable)', function (obj) {
                var oldData = table.cache[layTableId];
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                    if(row.attr_value_name ==obj.data.attr_value_name&&row.id !=obj.data.id){
                        obj.update({attr_value_name:''});
                        $(this).val('');
                        layer.msg("名称不能重复", { icon: 5 }); //提示
                        return false;
                    }
                }
            })




            //监听工具条
            table.on('tool(dataTable)', function (obj) {
                var data = obj.data, event = obj.event, tr = obj.tr; //获得当前行 tr 的DOM对象;
                console.log(data);
                switch(event){
                    case "type":
                        //console.log(data);
                        var select = tr.find("select[name='type']");
                        if(select){
                            var selectedVal = select.val();
                            if(!selectedVal){
                                layer.tips("请选择一个分类", select.next('.layui-form-select'), { tips: [3, '#FF5722'] }); //吸附提示
                            }
                            console.log(selectedVal);
                            $.extend(obj.data, {'type': selectedVal});
                            activeByType('updateRow', obj.data);	//更新行记录对象
                        }
                        break;
                    case "state":
                        var stateVal = tr.find("input[name='attr_value_status']").prop('checked') ? 1 : 0;
                        $.extend(obj.data, {'attr_value_status': stateVal})
                        activeByType('updateRow', obj.data);	//更新行记录对象
                        break;
                    case "del":
                        layer.confirm('真的删除行么？', function(index){
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            layer.close(index);
                            activeByType('removeEmptyTableCache');
                        });
                        break;
                }
            });



            //表单初始赋值
            form.val('formData', {
                "attr_name": "{{$data->attr_name}}"
                ,"attr_english": "{{$data->attr_english}}"
                ,"attr_status" : "{{$data->attr_status==1 ? 'on' : ''}}"

            });


            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                var oldData = table.cache[layTableId];
                console.log(oldData);
                for(var i=0, row; i < oldData.length; i++){
                    row = oldData[i];
                    if(!row.attr_value_name){
                        layer.msg("检查每一行，请完善数据！", { icon: 5 }); //提示
                        return false;
                    }
                }

                if(data.field.attr_status == "on") {
                    data.field.attr_status = "1";
                } else {
                    data.field.attr_status = "0";
                }
                data.field.table=table.cache;
                $.ajax({
                    url:"{{url('admins/attribute/'.$data->id)}}",
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
