@extends('erp.father.father')
@section('content')
<div class="layui-row" style="margin-top:10px;">
        <form class="layui-form" action="" id ="fm_import">
            <div class="layui-form-item">
                <label class="layui-form-label">选择文件</label>
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" id="import">导入数据</button>
                </div>

                <label class="layui-form-label">
                    <a href="{{ asset('templates/入库模板.xlsx') }}">
                        <span style="color:red;">下载模板</span>
                    </a>
                </label>
            </div>
        </form>
    </div>
    @endsection


    @section('js')

    <script>
          layui.use(['table', 'upload','layer', 'laydate'], function(){
            var table = layui.table,
                layer = layui.layer,
                $=layui.jquery
                , tablePlug = layui.tablePlug //表格插件
                , testTablePlug = layui.testTablePlug ;// 测试js模块
                var upload = layui.upload;
                var laydate = layui.laydate;

                 // //执行实例
            var uploadInst = upload.render({
                elem: '#import' //绑定元素
                ,url: '/admins/uploader/pic_upload' //上传接口
                ,accept: 'file' //所有文件
                ,exts: 'xls|xlsx' //后缀
                ,data: {_token:"{{ csrf_token() }}"}
                ,done: function(res){
                    //上传完毕回调
                    // console.log(res, $(".country_id:checked"));
                    if(res.code == 0){
                        $.ajax({
                            type:'POST',
                            url: "{{route('inventory.import')}}",
                            data:{
                                path:res.path,
                                _token:"{{ csrf_token()}}",
                                warehouse_id:{{ $warehouse_id }}
                            },
                            success:function(msg){
                                console.log(msg);
                                layer.open({
                                    title: '提示',
                                    content: msg,
                                    yes:function(index, layero){

                                        layer.closeAll();

                                        parent.layer.close(index);

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
          })
    </script>

@endsection
