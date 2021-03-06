@extends('erp.father.father')
@section('content')
    <div class="layui-fluid layui-card">
        <form class="layui-form" action="">
            {{csrf_field()}}
            <fieldset class="layui-elem-field layui-field-title">
                <legend>拣货需求配置单</legend>
            </fieldset>
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">拣货单编号</label>
                    <div class="layui-form-mid" style="color: #ff0000">* 拣货单编号自动生成</div>
                    <div class="layui-form-mid"></div>
                    <div class="layui-inline">
                        <label class="layui-form-label">拣货日期</label>
                        <div class="layui-input-inline">
                            <input type="text" name="picked_at" lay-verify="required" class="layui-input" id="dateTime" placeholder="yyyy-MM-dd HH:mm:ss">
                        </div>
                    </div>
                    <div class="layui-form-mid"></div>
                    <label class="layui-form-label">拣货仓库</label>
                    <div class="layui-input-inline">
                        <select name="warehouse_id">
                            <option value="0">请选择仓库</option>
                            @foreach($warehouse as $value)
                                <option value="{{$value->id}}">{{$value->warehouse_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">拣货人</label>
                <div class="layui-input-inline">
                    <input type="text" name="pick_name" placeholder="请输入拣货人" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">电话</label>
                <div class="layui-input-inline">
                    <input type="text" name="pick_phone" placeholder="请输入电话" autocomplete="off" class="layui-input">
                </div>
                <label class="layui-form-label">备注</label>
                <div class="layui-input-inline">
                    <input type="text" name="pick_text" placeholder="请输入备注信息" autocomplete="off" class="layui-input">
                </div>
            </div>
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                <legend>拣货订单详情</legend>
            </fieldset>


            <div id="dataTable" lay-filter="dataTable"></div>
            <script type="text/html" id="table_tool">
                <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
                <a class="layui-btn layui-btn-xs" lay-event="add_1">add-↑</a>
                <a class="layui-btn layui-btn-xs" lay-event="add_2">add-↓</a>
            </script>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="form">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>

    </div>
    <script type="text/html" id="status">
        @{{# if(d.ex_status == 0){ }} <div style="color: #ff0000">未出库</div> @{{# }else if(d.ex_status == 1){  }} <div style="color: #0000FF">拣货中</div>  @{{# }else{  }} <div style="color: #008000">已出库</div> @{{# }  }}
    </script>
    <script type="text/html" id="order_lock">
        @{{# if(d.order_lock == 0){ }} <div style="color: #ff0000">未锁定</div> @{{# }else{  }} <div style="color: #008000">已锁定</div> @{{# }  }}
    </script>

@endsection
@section('js')
    <script>
        //Demo
        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).use(['form','upload','laydate','table','layer'], function(){
            var form = layui.form
                ,laydate = layui.laydate
                ,table = layui.table
                ,layer = layui.layer;
            var $=layui.jquery;

            //日期时间选择器
            laydate.render({
                elem: '#dateTime'
                ,type: 'datetime'
            });

            var parent_json = eval('('+parent.json+')');

            var tableIns = table.render({
                elem: '#dataTable',
                height: 512,
                data: parent_json.data,
                cols: [[
                    //{title: '序号', type: 'numbers'},
                    {field: 'order_sn', title: '订单号', width: 150, fixed: 'left'}
                    ,{field: 'yunlu_sn', title: '运单号', width: 150, fixed: 'left'}
                    ,{title: '状态', width: 80, fixed: 'left',templet:'#status'}
                    ,{title: '锁定', width: 80, fixed: 'left',templet:'#order_lock'}
                    ,{field: 'id', title: 'ID', width:80, sort: true,}
                    ,{field: 'order_name', title: '收件人', width:100}
                    ,{field: 'order_phone', title: '电话', width:120}
                    ,{field: 'order_code', title: '邮编', width:80}
                    ,{field: 'order_province', title: '省', width:120}
                    ,{field: 'order_city', title: '市', width:120}
                    ,{field: 'order_county', title: '县', width:120}
                    ,{field: 'order_area', title: '区', width:120}
                    ,{field: 'order_address', title: '详细地址', width:220}
                    ,{field: 'ordered_at', title: '下单时间', width: 160, sort: true}
                ]],
            });


            //监听提交
            form.on('submit(form)', function(data){
                //layer.msg(JSON.stringify(data.field));
                data.field.table = table.cache;
                for(var i=0, row; i < table.cache.dataTable.length; i++){
                    row = table.cache.dataTable[i];
                    if(row.goods_num==0 || row.goods_num==''){
                        layer.msg("检查每一行，请完善数据！", { icon: 5 }); //提示
                        return false;
                    }
                }
                $.ajax({
                    url:"{{url('/admins/warehouse_pick')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function (msg) {
                        if(msg=='0'){
                            layer.msg('添加成功！',{icon:1,time:2000},function () {
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
