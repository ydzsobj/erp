@extends('erp.father.father')
@section('content')
    <style>
    /*数据表格内容换行显示*/
    .layui-table-cell {
        height: auto;
        width:50px;
                /*空白会被浏览器忽略*/
        /* white-space: normal; */
                /*允许长单词换行到下一行*/
        /* word-wrap: break-word; */
                /*允许在单词内换行*/
        /* word-break: break-all; */
    }
    </style>
    <div class="layui-row" style="margin-top:10px;">
            <form class="layui-form" action="">

                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">请输入</label>
                        <div class="layui-input-block">
                            <div class="layui-inline" style="width:300px;">
                                <input class="layui-input" name="sku_name" id="demoReload" placeholder="产品名称/订单编号/SKU编号"  autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">状态</label>
                        <div class="layui-input-inline">
                            <select name="status" id="search_status">
                                <option value="0">全部</option>
                                @foreach ($status_list as $key=>$status)
                                    <option value="{{ $key }}">{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-inline">
                            <label class="layui-form-label">下单时间</label>
                            <div class="layui-input-block">
                                <div class="layui-inline">
                                    <input class="layui-input" name="start_date" id="start_date" placeholder="开始时间">
                                </div>-
                                <div class="layui-inline">
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

    <table id="demo" lay-filter="test"></table>

@endsection

@section('hidden_dom')

    <form class="layui-form" action="" id ="fm_import" style="display:none;">
        <div class="layui-form-item">
                <label class="layui-form-label">国家地区</label>
                <div class="layui-input-block">
                    @foreach ($countries as $key=>$country_name)
                        <input type="radio" name="country_id" class="country_id" value="{{ $key }}" title="{{ $country_name }}">
                    @endforeach
                </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">选择文件</label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="import">上传</button>
            </div>
        </div>

    </form>
@endsection

@section('js')

    <script>
        layui.use(['table', 'upload','layer', 'laydate'], function(){
            var table = layui.table;
            var upload = layui.upload;
            var layer = layui.layer;
            var laydate = layui.laydate;
            var $ = layui.jquery;

            // //第一个实例
            table.render({
                elem: '#demo'
                ,url: '/api/orders' //数据接口
                ,page: true //开启分页
                ,limit:20//分页大小
                ,toolbar: '#toolbarDemo'
                // ,width:1800
                // ,cellMinWidth:50
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
                    ,{type: 'checkbox', width:50, fixed:'left'}
                    ,{field: 'submit_order_at', title: '下单时间',width:160}
                    ,{field: 'sn', title: '订单编号',width:180}
                    ,{field: 'amount', title: '总件数',width:80}

                    ,{field: 'order_skus', title: '商品名称',width:180,
                        templet: function(d){
                            var skus = d.order_skus;
                            var str = '';
                            for(var i=0;i<skus.length;i++){
                                str += "<span>" + skus[i].sku.sku_name + '</span><span> &nbsp;&nbsp;x' + skus[i].sku_nums  + "</span><br />";
                            }
                            return str;
                        }
                    }

                    ,{field: 'order_skus', title: 'SKU',width:180,
                        templet: function(d){
                            var skus = d.order_skus;
                            var str = '';
                            for(var i=0;i<skus.length;i++){
                                str += "<span>" + skus[i].sku_id + "</span><br />";
                            }
                            return str;
                        }
                    }
                    ,{field: 'order_skus', title: '属性',width:120,
                        templet: function(d){
                            var skus = d.order_skus;
                            var str = '';
                            for(var i=0;i<skus.length;i++){
                                str += "<span>" + skus[i].sku.sku_value + "</span><br />";
                            }
                            return '红色|39';
                        }
                    }
                    ,{field: 'receiver_name', title: '收货人',width:150}
                    ,{field: 'country_name', title: '国家',width:120 }
                    ,{field: 'status_name', title: '状态', width:80,
                        templet:function(row){
                            var color = '';
                            if(row.status == 1){
                                color = 'red';
                            }else if(row.status == 2){
                                color = 'green';
                            }else if(row.status == 6){
                                color = 'orange';
                            }

                            return "<span style='color:" + color +"'>" + row.status_name +"</span>";
                        }
                    }
                    ,{field: 'admin_user', title: '创建人', width:120,
                        templet:function(row){
                            return row.admin_user.admin_name;
                        }
                    }
                    ,{field: 'audited_admin_user', title: '审核人', width:120,
                        templet:function(row){
                            return row.audited_admin_user.admin_name;
                        }
                    }

                    ,{title: '操作', width:150, fixed:'right',
                         templet: function(row){
                             if(row.status == 1){
                                return '<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>' +
                                    '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="audit">审核</a>';
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
                    autoFixedHeight(this.elem[0]);
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
                            start_date:$("#start_date").val(),
                            end_date:$("#end_date").val(),

                        }
                    }, 'data');
                }
            };
            //点击搜索
            $('#search').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

            laydate.render({
                elem: '#start_date'
                ,type: 'datetime'
            });

            laydate.render({
                elem: '#end_date'
                ,type: 'datetime'
            });

            //监听头部工具条
            table.on('toolbar(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(obj);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

                var checkStatus = table.checkStatus(obj.config.id);

                console.log(checkStatus.data);

                if(layEvent === 'import_order'){ //
                    //do somehing
                    console.log('click import order');
                    layer.open({
                        title:'订单导入',
                        type: 1,
                        area:['600px','300px'],
                        content: $("#fm_import")
                    })
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

                    layer.confirm('确定要审核通过吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: "{{ route('orders.batch_audit') }}",
                            data:{ _token: "{{ csrf_token() }}" ,order_ids: selected_ids },
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

                }
            });

            //监听行内工具条
            table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data; //获得当前行数据
                console.log(data);
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

                var route = '/admins/orders/update_audited_at/' + data.id;

                if(layEvent === 'audit'){ //
                    //do somehing
                    layer.confirm('确定要审核通过吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: route,
                            data:{ _token: "{{ csrf_token() }}" ,action: 'audit'},
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
                }else if(layEvent == 'cancel_order'){
                    //取消订单
                    layer.confirm('确定要取消订单吗?', function(index){
                        layer.close(index);
                        //向服务端发送指令
                        $.ajax({
                            type:'POST',
                            url: route,
                            data:{ _token: "{{ csrf_token() }}",action:'cancel_order'},
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

                    console.log(table_str);

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

                    //同步更新缓存对应的值
                    obj.update({
                    username: '123'
                    ,title: 'xxx'
                    });
                } else if(layEvent === 'LAYTABLE_TIPS'){
                    layer.alert('Hi，头部工具栏扩展的右侧图标。');
                }
            });

            // //执行实例
            var uploadInst = upload.render({
                elem: '#import' //绑定元素
                ,url: '/admins/uploader/pic_upload' //上传接口
                ,accept: 'file' //所有文件
                ,exts: 'xls|xlsx' //后缀
                ,data: {_token:"{{ csrf_token() }}"}
                ,done: function(res){
                    //上传完毕回调
                    console.log(res, $(".country_id:checked"));
                    if(res.code == 0){
                        $.ajax({
                            type:'POST',
                            url: "{{route('orders.import')}}",
                            data:{
                                path:res.path,
                                country_id: $(".country_id:checked").val(),
                                _token:"{{ csrf_token()}}"
                            },
                            success:function(msg){
                                console.log(msg);
                                layer.open({
                                    title: '提示',
                                    content: msg,
                                    yes:function(index, layero){
                                        table.reload('demo', {
                                            where: { //设定异步数据接口的额外参数，任意设
                                                aaaaaa: 'xxx'
                                                ,bbb: 'yyy'
                                                //…
                                            }
                                            ,page: {
                                                curr: 1 //重新从第 1 页开始
                                            }
                                            }); //只重载数据

                                        layer.closeAll();

                                        // window.location.reload();
                                    }
                                });
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
                    }else{
                        layer.msg('上传失败，请重新上传');
                    }
                }
                ,error: function(){
                    //请求异常回调
                }
            });

             /**
             * 根据表格宽度自动显示右侧浮动栏,并修正浮动栏高度
             * @param tableElem 绑定元素的dom
             */
            function autoFixedHeight(tableElem) {
                // 获取表格div
                var $tableView = $(tableElem).next(".layui-table-view");
                // 获取两侧浮动栏
                var $fixed = $tableView.find(".layui-table-fixed");
                var dataIndex;
                var trHeight;
                // 遍历tr 修正浮动栏行高
                $tableView.find(".layui-table-main").find("tr").each(function() {
                    dataIndex = $(this).attr("data-index");
                    trHeight = $(this).css("height");
                    $fixed.find("tr[data-index=" + dataIndex + "]").css("height", trHeight);
                });
            }

        });


</script>

 <script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container">
      <button class="layui-btn layui-btn-sm" lay-event="import_order" >导入订单</button>
      <button class="layui-btn layui-btn-sm" lay-event="batch_audit" >批量审核</button>
    </div>
  </script>

@endsection
