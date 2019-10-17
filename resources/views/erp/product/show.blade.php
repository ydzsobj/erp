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
            <label class="layui-form-label">产品名称:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_name}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">英文名称:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_english}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">分类ID:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->category_id}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">分类名称:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->category_name}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">类型ID:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->type_id}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">类型名称:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->type_name}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">品牌ID:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->brand_id}}</div>
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
            <label class="layui-form-label">产品条形码:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->barcode}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品SPU:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_spu}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">规格数值:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->spec_value}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">成本价:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_cost_price}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">销售价:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_price}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">库存报警:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_storage_alarm}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品运费:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_freight}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品尺寸:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_size}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品重量:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_weight}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">主供应商链接:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->url}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">辅供应商链接:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->supplier_burl}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品推荐:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_commend}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
        <div class="layui-form-item">
            <label class="layui-form-label">产品状态:</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{$data->product_state}}</div>
            </div>
        </div>
        <hr class="layui-bg-gray">
    </div>
@endsection
