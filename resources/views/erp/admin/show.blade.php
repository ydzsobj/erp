@extends('erp.father.static')
@section('content')
    <!DOCTYPE html>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">设置我的资料</div>
                <div class="layui-card-body" pad15>

                    <form class="layui-form layui-form-pane " method="post" lay-filter="" action="">
                        {{csrf_field()}}
                        {{--<div class="layui-form-item" >--}}
                        {{--<label class="layui-form-label">我的角色</label>--}}
                        {{--<div class="layui-input-inline" >--}}
                        {{--<select name="role" lay-verify="">--}}
                        {{--@foreach(\App\role::get() as $k => $v)--}}
                        {{--<option value="{{$v->role_id}}" @if(Auth::user()->admin_role_id==$v->role_id) selected @else disabled @endif>{{$v->role_name}}</option>--}}
                        {{--@endforeach--}}
                        {{--</select> --}}
                        {{--</div>--}}
                        {{--<div class="layui-form-mid layui-word-aux">当前角色不可更改为其它角色</div>--}}
                        {{--</div>--}}
                        <div class="layui-form-item">
                            <label class="layui-form-label">用户名</label>
                            <div class="layui-input-inline">
                                <input type="text" name="username" value="{{Auth::user()->username}}" disabled class="layui-input">
                            </div>
                            <div class="layui-form-mid layui-word-aux">不可修改。一般用于后台登入名</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">登陆时间</label>
                            <div class="layui-input-inline">
                                <input type="text" name="updated_at" value="{{Auth::user()->updated_at}}" disabled class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">登陆ip</label>
                            <div class="layui-input-inline">
                                <input type="text" name="admin_ip" value="{{Auth::user()->admin_ip}}" disabled class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">真实姓名</label>
                            <div class="layui-input-inline">
                                <input type="text" name="admin_name"  value="{{Auth::user()->admin_name}}" lay-verify="required|admin_show_name" autocomplete="off" placeholder="请输入真实姓名" class="layui-input">
                            </div>
                        </div>
                        {{--<div class="layui-form-item" >--}}
                        {{--<label class="layui-form-label">名下单品数</label>--}}
                        {{--<div class="layui-input-inline">--}}
                        {{--<input type="text" name="goods_count" disabled value="{{\App\goods::where([['goods_admin_id',Auth::user()->admin_id],['is_del','0']])->count()}}"  autocomplete="off" placeholder="" class="layui-input">--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="">确认修改</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重新填写</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        layui.config({
            base: '{{asset("/admin/layuiadmin/")}}/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'set','admin'],function(){
            var form=layui.form
            var admin=layui.admin
            var $=layui.jquery
            form.verify({
                admin_show_name: function(value, item){ //value：表单的值、item：表单的DOM对象
                    if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                        return '真实姓名不能有特殊字符';
                    }
                    if(/(^\_)|(\__)|(\_+$)/.test(value)){
                        return '真实姓名首尾不能出现下划线\'_\'';
                    }
                    if(/^\d+\d+\d$/.test(value)){
                        return '真实姓名不能全为数字';
                    }
                }
            });
            form.on('submit',function(data){
                var index = layer.load();
                $.ajax({
                    url:"{{url('admins/up_self')}}",
                    type:'post',
                    data:data.field,
                    datatype:'json',
                    success:function(msg){
                        if(msg['err']==1){
                            layer.close(index);
                            layer.msg(msg.str,{
                                time: 2000 //2秒关闭（如果不配置，默认是3秒）
                            }, function(){
                                parent.layui.admin.events.refresh();
                            });
                        }else if(msg['err']==0){
                            layer.close(index);
                            layer.msg(msg.str);
                        }else{
                            layer.close(index);
                            layer.msg('修改失败！');
                        }
                    }
                })
                return false;
            })
        });

    </script>
@endsection
