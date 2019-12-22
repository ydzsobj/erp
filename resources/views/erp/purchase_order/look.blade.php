@extends('erp.father.father')
@section('content')
    <ul class="layui-timeline">
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis"></i>
            <div class="layui-timeline-content layui-text">
                <div class="layui-timeline-title">预计出货时间：{{$data->expect_out_at}}</div>
            </div>
        </li>
        <li class="layui-timeline-item">
            <i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop layui-timeline-axis"></i>
            <div class="layui-timeline-content layui-text">
                <div class="layui-timeline-title">预计到货时间：{{$data->expect_deliver_at}}</div>
            </div>
        </li>
    </ul>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>物流时间线</legend>
    </fieldset>
    <ul class="layui-timeline">
        @foreach($trace as $value)
        <li class="layui-timeline-item">
            <i class="layui-icon layui-timeline-axis"></i>
            <div class="layui-timeline-content layui-text">
                <h3 class="layui-timeline-title">{{$value->created_at}}</h3>
                <p>{{$value->purchase_order_log}}</p>
            </div>
        </li>
        @endforeach
        <hr>
        @if(!$note->isEmpty())
            <li class="layui-timeline-item">
                <i class="layui-icon layui-timeline-axis"></i>
                <div class="layui-timeline-content layui-text">
                    <h3 class="layui-timeline-title">物流轨迹：</h3>
                    <ul>
                        @foreach($note as $value)
                        <li>地点：{{$value->logistics_log}} &nbsp;&nbsp;&nbsp;&nbsp; [{{$value->created_at}}]</li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @endif
    </ul>

@endsection
@section('js')

@endsection
