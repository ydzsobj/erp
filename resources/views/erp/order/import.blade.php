@extends('erp.father.father')
@section('content')

<form class="layui-form" action="">

<div class="layui-form-item">
        <label class="layui-form-label">国家地区</label>
        <div class="layui-input-block">
        <input type="radio" name="sex" value="1" title="印尼">
        <input type="radio" name="sex" value="2" title="菲律宾" checked>
        </div>
</div>

<div class="layui-form-item">
    <label class="layui-form-label">选择文件</label>
    <div class="layui-input-block">
        <button type="button" class="layui-btn" id="import">上传</button>
    </div>
</div>

{{-- <div class="layui-form-item">
    <div class="layui-input-block">
    <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formDemo">立即导入</button>
    </div>
</div> --}}
</form>

@endsection

@section('js')
<script>
        //Demo
        layui.use(['table','form', 'upload'], function(){
            var table = layui.table;
            var form = layui.form;
            var upload = layui.upload;
            var layer = layui.layer;
            var $ = layui.jquery;
            //监听提交
            form.on('submit(formDemo)', function(data){
                layer.msg(JSON.stringify(data.field));
                return false;
            });
        });


    </script>
@endsection




