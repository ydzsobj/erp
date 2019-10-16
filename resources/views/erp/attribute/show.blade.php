@extends('erp.father.father')
@section('content')
    <div class="layui-card">
        <div class="layui-form-item">
            <label class="layui-form-label">ID:</label>
            <div class="layui-input-block">
                {{$data->id}}
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">分类名称:</label>
            <div class="layui-input-block">
                {{$data->name}}
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">父级ID:</label>
            <div class="layui-input-block">
                {{$data->parent_id}}
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">类型ID:</label>
            <div class="layui-input-block">
                {{$data->type_id}}
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">类型名称:</label>
            <div class="layui-input-block">
                {{$data->type_name}}
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">排序:</label>
            <div class="layui-input-block">
                {{$data->sort}}
            </div>
        </div>
        <hr class="layui-bg-gray">
    </div>
@endsection
