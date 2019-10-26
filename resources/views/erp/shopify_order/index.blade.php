@extends('erp.father.father')
@section('content')

<style>
        html,body{
          height: 100%;
        }
        .split-pane-warpper{
          width: 100%;
          height: 100%;
          position: relative;

        }
        .pane{
          width: 100%;
          position:absolute;

        }
        .pane-top{
          /* background-color: palevioletred; */
           height: calc(50% - 3px);
          overflow: auto

        }
        .pane-bottom{
          /* background-color:pink; */
          bottom: 0;
          top: calc(50% + 3px);
          overflow: auto
        }
        .pane-trigger-con{
          width: 100%;
          background-color: red;
          position: absolute;
          z-index: 9;
          user-select: none;
          top: calc(50% - 3px);
          height: 6px;
          cursor: row-resize;
        }
        .layui-form-label{
          padding: 9px 0
        }

        .layui-table-cell {
            width:50px;
        }
      </style>

@section('hidden_dom')

<form class="layui-form" action="" id="audit_window" style="display:none;margin:5px;">
        <div class="layui-form-item">
            <label class="layui-form-label"> <span style="color:red;">* </span>选择状态</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select name="status" id="audit_status" lay-verify="required" lay-reqtext="不能为空">
                        @foreach ($audit_status_options as $key=>$option )
                            <option value="{{ $key }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    <div class="layui-form-item">
        <label class="layui-form-label"> <span style="color:red;">* </span>备注内容</label>
        <div class="layui-input-block">
            <textarea name="remark" id="audit_remark" lay-verify="required" lay-reqtext="不能为空"></textarea>
        </div>
    </div>

</form>
@endsection

<!--筛选开始-->
<div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="">

            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">请输入</label>
                    <div class="layui-input-block">
                        <div class="layui-inline" style="width:220px;">
                            <input class="layui-input" name="sku_name" id="demoReload" placeholder="产品名称/订单编号/SKU编号"  autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline" style="width:100px;">
                        <select name="status" id="search_status">
                            <option value="0">全部</option>
                            @foreach ($status_list as $key=>$status)
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="layui-inline">
                        <label class="layui-form-label">国家</label>
                        <div class="layui-input-inline" style="width:100px;">
                            <select name="country_id" id="search_country_id">
                                <option value="0">全部</option>
                                @foreach ($countries as $key=>$country)
                                    <option value="{{ $key }}">{{ $country['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <div class="layui-inline">
                        <div class="layui-input-block">
                            <div class="layui-inline" style="width:100px;">
                                <select id="select_date_type">
                                    <option value="1">下单时间</option>
                                    <option value="2">审核时间</option>
                                </select>
                            </div>
                            <div class="layui-inline" style="width:150px;">
                                <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间">
                            </div>-
                            <div class="layui-inline" style="width:150px;">
                                <input class="layui-input" name="end_date" id="end_date" placeholder="结束时间">
                            </div>
                        </div>
                    </div>
            </div>

            <div class="layui-row demoTable">
                <a class="layui-btn" data-type="reload" style="margin-left:600px;" id='search'>搜索</a>
                &nbsp;<button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>

        </form>
</div>
<!--表格开始-->
    <div style="width: 100%;height: calc(100% - 92px);">
      <div class="split-pane-warpper">
        <div class="pane pane-top" >
            <div class="layui-card-body">
                <table id="demo" lay-filter="test"></table>
            </div>
        </div>
        <div class="pane pane-trigger-con"></div>
        <div class="pane pane-bottom" >
         {{-- <table class="layui-hide" id="LAY_table_user" lay-filter="user"></table> --}}
          <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-tab layui-tab-card">
                        <ul class="layui-tab-title">
                            <li class="layui-this"><span id="sku_detail"></span> 详情</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                            <table class="layui-hide" id="sku_table" lay-filter="sku"></table>
                            </div>
                        </div>
                        </div>
                </div>
                </div>
            </div>
</div>

@endsection

@section('js')

<script>

    layui.use(['table', 'upload','layer', 'laydate'], function(){

        var table = layui.table;
        var upload = layui.upload;
        var layer = layui.layer;
        var laydate = layui.laydate;
        var $ = layui.jquery;

        table.render({
            elem: '#demo'
            ,url: '/api/orders' //数据接口
                ,page: true //开启分页
                ,limit:20//分页大小
                ,toolbar: '#toolbarDemo'
                ,defaultToolbar: ['']
                ,parseData: function(res){ //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.count, //解析数据长度
                        "data": res.data.data //解析数据列表
                    };
                }
                ,cols: [[ //
                    ,{type: 'checkbox', width:50}
                    ,{field: 'submit_order_at', title: '下单时间',width:170}
                    ,{field: 'sn', title: '订单编号',width:150}
                    ,{field: 'amount', title: '件数',width:60}
                    ,{field: 'price', title: '价格', width:100,sort:true}
                    ,{field: 'currency_code', title: '币种', width:80}
                    ,{field: 'receiver_name', title: '收货人',width:150}
                    ,{field: 'receiver_phone', title: '收货电话',width:150}
                    ,{field: 'province', title: '省',width:120}
                    ,{field: 'city', title: '市',width:120}
                    ,{field: 'area', title: '区',width:120}
                    ,{field: 'address1', title: '详细地址1',width:120}
                    ,{field: 'address2', title: '详细地址2',width:100}
                    ,{field: 'company', title: '公司',width:100}

                    ,{field: 'country_name', title: '国家',width:80 }

                    ,{field: 'audited_admin_user', title: '审核人', width:100,
                        templet:function(row){
                            return row.audited_admin_user.admin_name || '';
                        }
                    }
                    ,{field: 'status_name', title: '状态', width:80 , fixed:'right',
                        templet:function(row){
                            var color = '';
                            if(row.status == 1){
                                color = 'red';
                            }else if(row.status == 2){
                                color = 'green';
                            }else if(row.status == 7){
                                color = 'orange';
                            }else if(row.status == 6){
                                color = 'pink';
                            }

                            return "<span style='color:" + color +"'>" + row.status_name +"</span>";
                        }
                    }
                    ,{field: 'remark', title: '客服备注',width:100 ,edit:true,fixed:'right' }
                    ,{title: '操作', width:150, fixed:'right',
                         templet: function(row){
                             if(row.status == 1){
                                return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>' +
                                    '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="audit">审核</a>' ;
                             }else if(row.status == 7){
                                return '<a class="layui-btn layui-btn-xs" lay-event="audit_logs">审核记录</a>';

                             }else if(row.status == 2){
                                return '<a class="layui-btn layui-btn-xs" lay-event="audit_logs">审核记录</a>' +
                                 '<a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="cancel_order">取消</a>';
                             }else{
                                 return '<a class="layui-btn layui-btn-xs" lay-event="audit_logs">审核记录</a>';
                             }
                         }
                     }
                ]],
                done: function() {
                }
        });

        //子数据
        table.render({
            elem: '#sku_table'
            // ,url: layui.setter.base +'json/table/user.js'
            ,cols: [[
            ,{field:'sku', title: 'SKU编码',
                templet:function(row){
                    console.log(row);
                    return row.sku_id;
                }
            }
            ,{field:'sku', title: '产品名称',
                templet:function(row){
                    return row.sku.sku_name || '';
                }
            }
            ,{field:'sku', title: '属性',
                templet:function(row)
                {
                   return row.sku.sku_attr_value_names || '';
                }
            }
            ,{field:'sku_nums', title: '数量', sort: true}

            ]]
            ,data:[]
        });

        table.on('row(test)', function(obj){
            var data = obj.data;
            table.reload('sku_table', {
                data:data.order_skus
            });

            $("#sku_detail").text('单号：' + data.sn);

            //标注选中样式
            obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
        });

        //监听行内工具条
        table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            console.log(data);
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

            var route = '/admins/orders/' + data.id + '/update_audited_at';

            if(layEvent === 'audit'){ //
                layer.open({
                    type:2,
                    title:'审核',
                    content: "/admins/orders/" + data.id + '/create_audit',
                    area: ['500px', '300px'],
                });

            }else if(layEvent == 'cancel_order'){
                //取消订单
                layer.confirm('确定要取消订单吗?', function(index){
                    layer.close(index);
                    //向服务端发送指令
                    $.ajax({
                        type:'POST',
                        url: route,
                        data:{ _token: "{{ csrf_token() }}",_method:'put', status:6, remark:'订单取消'},
                        dataType:"json",
                        success:function(msg){
                                console.log(msg);
                                layer.msg(msg.msg);
                                if(msg.success){
                                table.reload('demo');
                                }
                        },
                        error: function(data){
                            layer.msg('请求接口失败',{icon:2,time:2000});
                        }

                    })
                });

            }else if(layEvent == 'audit_logs'){
                //审核记录
                var table_str = '<table class="layui-table"><tr><th>时间</th><th>审核人</th><th>备注</th></tr>';
                var audit_logs = data.audit_logs;
                console.log(audit_logs);
                for(var i=0;i<audit_logs.length;i++){
                    var tr_str = '<tr><td>' + audit_logs[i].created_at +'</td><td>' + audit_logs[i].admin_user.admin_name
                        +'</td><td>' + audit_logs[i].remark +'</td></tr>';
                    table_str += tr_str;
                }
                table_str += '</table>';

                // console.log(table_str);

                layer.open({
                    title:'审核记录',
                    content: table_str,
                    area:['500px','300px']
                });

            } else if(layEvent === 'del'){ //删除
                layer.confirm('真的删除行么', function(index){
                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                layer.close(index);
                //向服务端发送删除指令
                });
            } else if(layEvent === 'edit'){ //编辑
                //do something
                layer.open({
                        skin:'layui-layer-nobg',
                        type:2,
                        title:'编辑订单',
                        area:['800px','700px'],
                        fixed:false,
                        maxmin:true,
                        content:"{{url('admins/orders/')}}/"+data.id+"/edit"
                    });
            } else if(layEvent === 'LAYTABLE_TIPS'){
                layer.alert('Hi，头部工具栏扩展的右侧图标。');
            }
        });

        //监听编辑行
        table.on('edit(test)', function(obj){ //注：edit是固定事件名，test是table原始容器的属性 lay-filter="对应的值"
            console.log(obj.value); //得到修改后的值
            console.log(obj.field); //当前编辑的字段名
            console.log(obj.data); //所在行的所有相关数据

            if(obj.field == 'remark'){
                $.ajax({
                    type:'post',
                    url:"/admins/orders/" + obj.data.id + '/update_remark',
                    data:{_token:"{{ csrf_token() }}",_method: 'put', remark: obj.value},
                    success:function(msg){
                        if(msg.success){
                            layer.msg(msg.msg);
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
                })
            }

        });

        //监听头部工具条
        table.on('toolbar(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            console.log(obj);
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

            var checkStatus = table.checkStatus(obj.config.id);

            console.log(checkStatus.data);

            if(layEvent === 'export_order'){ //
                //do somehing
                console.log('click export order');

                var params = {
                    keywords: $("#demoReload").val(),
                    status: $("#search_status").val(),
                    country_id: $("#search_country_id").val(),
                    select_date_type: $("#select_date_type").val(),
                    start_date:$("#start_date").val(),
                    end_date:$("#end_date").val(),
                };

                var route = "{{ route('orders.export') }}";
                var href = route + '?'+ encodeSearchParams(params);
                console.log(href);
                location.href = href;

            }else if(layEvent == 'batch_audit'){
                //批量审核
                if(checkStatus.data.length == 0){
                    layer.msg('请先选择订单');
                    return false;
                }

                var selected_rows = checkStatus.data;
                var selected_ids = [];
                for(var i=0;i<selected_rows.length;i++){
                    selected_ids.push(selected_rows[i].id);
                }
                console.log(selected_ids);

                layer.open({
                    type:1,
                    title: '批量审核',
                    content: $("#audit_window"),
                    area: ['500px', '300px'],
                    btn:['确定'],
                    yes:function(index){
                        console.log(index);
                        $.ajax({
                            type:'POST',
                            url: "{{ route('orders.batch_audit') }}",
                            data:{
                                 _token: "{{ csrf_token() }}" ,
                                 order_ids: selected_ids,
                                 status:$("#audit_status").val(),
                                 remark:$("#audit_remark").val(),
                            },
                            dataType:"json",
                            success:function(msg){
                                    layer.close(index);
                                    console.log(msg);
                                    layer.msg(msg.msg);
                                    if(msg.success){
                                    table.reload('demo');
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

                        })
                    },
                });

            }
        });

        //搜索条件
        var active = {
            reload: function(){
                var demoReload = $('#demoReload');
                console.log('do reload');
                //执行重载
                table.reload('demo', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        keywords: demoReload.val(),
                        status: $("#search_status").val(),
                        country_id: $("#search_country_id").val(),
                        start_date:$("#start_date").val(),
                        end_date:$("#end_date").val(),
                        select_date_type: $("#select_date_type").val(),

                    }
                }, 'data');
            }
        };
        //点击搜索
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
            console.log('11');
            // table.reload('sku_table');
            table.reload('sku_table', {
                data:[]
            });
            $("#sku_detail").text('');
        });

        laydate.render({
            elem: '#start_date'
            ,type: 'datetime'
        });

        laydate.render({
            elem: '#end_date'
            ,type: 'datetime'
        });

        var conMove = false;
        $('.pane-trigger-con').mousedown(function(event){
            conMove = true
            $(document).mousemove(function  (event){
                if (!conMove) return
                var pageY=event.pageY-92
                if (pageY < 100) pageY = 100
                if (pageY > $('.split-pane-warpper').height()-40) pageY = $('.split-pane-warpper').height()-40
                $('.pane-top').height(pageY)
                $('.pane-bottom').css('top',pageY)
                $('.pane-trigger-con').css('top',pageY)
            })
            $(document).mouseup(function  (event){
                // console.log(event)
                conMove = false
            })
        })



  });
  </script>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="batch_audit" >批量审核</button>
      <a class="layui-btn layui-btn-sm" lay-event="export_order" >导出订单</a>
    </div>
  </script>

  <script>

/**
 * 拼接对象为请求字符串
 * @param {Object} obj - 待拼接的对象
 * @returns {string} - 拼接成的请求字符串
 */
function encodeSearchParams(obj) {
  const params = []

  Object.keys(obj).forEach((key) => {
    let value = obj[key]
    // 如果值为undefined我们将其置空
    if (typeof value === 'undefined') {
      value = ''
    }
    // 对于需要编码的文本（比如说中文）我们要进行编码
    params.push([key, encodeURIComponent(value)].join('='))
  })

  return params.join('&')
}
  </script>

@endsection
