@extends('erp.father.father')
@section('content')
    <div class="layui-card">
        <div class="layui-form-item">
            <label class="layui-form-label">ID:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->id}}</div>

            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">品牌名称:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->brand_name}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">品牌简介:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->brand_title}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">品牌图片:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux"><img class="layui-upload-img" style="max-width: 300px; margin: 0 10px 10px 0;" src="{{$data->brand_pic}}"></div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">排序:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->sort}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
    </div>
@endsection
