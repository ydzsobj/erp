<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo" lay-href="/admin/index">
            <i class="layui-icon">&#xe857;</i>
            <span> 仓储数据管理系统</span>
        </div>
        <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
            lay-filter="layadmin-system-side-menu">
            <li data-name="home" class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;" lay-tips="主页" lay-direction="2">
                    <i class="layui-icon layui-icon-home"></i>
                    <cite>主页</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console" class="layui-this">
                        <a lay-href="home/console.html"><i class="layui-icon">&#xe629;</i>监控台</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="产品管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>产品管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">产品列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/category')}}">分类列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/type')}}">类型列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/attribute')}}">属性列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/attribute_value')}}">属性值列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/brand')}}">品牌列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product_goods')}}">产品SKU</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product_unit')}}">计量单位</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="订单管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>订单管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{ url('/admins/order/import') }}">订单导入</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{ url('/admins/order') }}">待采汇总</a>
                    </dd>
                    {{--<dd data-name="console">
                        <a lay-href="{{route('orders.index') }}">SHOPIFY订单</a>
                    </dd>--}}
                    <dd data-name="console">
                        <a lay-href="{{ url('/admins/order/list') }}">订单列表</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="采购管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>采购管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/purchase_pool')}}">采购汇总</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/purchase_order')}}">采购订单</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/purchase_return')}}">采购退货出库</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/supplier')}}">供应商列表</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="仓库管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>仓库管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="grid">
                        <a href="javascript:;">深圳仓<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/warehouse/1')}}">总览</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/purchase_warehouse/1')}}">验收入库</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/warehouse_in/1')}}">入库列表</a>
                            </dd>
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/inventory/1')}}">产品库存</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/warehouse_pick/1')}}">拣货列表</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/warehouse_out/1')}}">出库列表</a>
                            </dd>
                            {{--<dd data-name="console">
                                <a lay-href="{{url('/admins/inventory_transfer')}}">库间调拨</a>
                            </dd>--}}
                            <dd data-name="mobile">
                                <a lay-href="{{url('/admins/inventory_check/1')}}">库存盘点</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/problem/1')}}">问题订单</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="grid">
                        <a href="javascript:;">海外仓<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/guide?warehouse_id=3')}}">印尼仓</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/guide?warehouse_id=4')}}">印尼虚拟仓</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="grid">
                        <a href="javascript:;">仓库总账<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/inventory/all')}}">产品库存</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/purchase_warehouse')}}">验收入库</a>
                            </dd>
                           {{-- <dd data-name="console">
                                <a lay-href="{{url('/admins/inventory_transfer')}}">库间调拨</a>
                            </dd>
                            <dd data-name="mobile">
                                <a lay-href="{{url('/admins/inventory_check')}}">库存盘点</a>
                            </dd>--}}
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/warehouse_out')}}">出库列表</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/warehouse_pick')}}">拣货列表</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/warehouse')}}">仓库列表</a>
                    </dd>

                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="物流管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>物流管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/warehouse_ex')}}">运单列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/warehouse_out')}}">出库列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/warehouse_pick')}}">拣货列表</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="财务管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>财务管理</cite>
                </a>
                {{--<dl class="layui-nav-child">
                    <dd data-name="grid">
                        <a href="javascript:;">栅格<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="list">
                                <a lay-href="component/grid/list.html">等比例列表排列</a>
                            </dd>
                            <dd data-name="mobile">
                                <a lay-href="component/grid/mobile.html">按移动端排列</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="component/button/index.html">按钮</a>
                    </dd>

                </dl>--}}
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="用户管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>用户管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/admin')}}">管理员列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">角色列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">权限列表</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/salesman')}}">销售人员</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/admin/log')}}">登录日志</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="问题处理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>问题处理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="grid">
                        <a href="javascript:;">问题订单<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/all')}}">总览</a>
                            </dd>
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/order')}}">自建站问题订单</a>
                            </dd>
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/purchase_order')}}">采购问题订单</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/problem/purchase_warehouse')}}">入库问题订单</a>
                            </dd>
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/inventory_check')}}">盘点问题订单</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/problem/warehouse_pick')}}">拣货问题订单</a>
                            </dd>
                            <dd data-name="console">
                                <a lay-href="{{url('/admins/problem/warehouse_out')}}">出库问题订单</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="grid">
                        <a href="javascript:;">问题件<span class="layui-nav-more"></span></a>
                        <dl class="layui-nav-child">
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/lost')}}">商品丢失</a>
                            </dd>
                            <dd data-name="list">
                                <a lay-href="{{url('/admins/problem/torn')}}">包装破损</a>
                            </dd>
                        </dl>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/problem')}}">问题列表</a>
                    </dd>

                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="用户管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>系统管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">系统设置</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">站点设置</a>
                    </dd>
                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="shopify店铺管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>shopify配置</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{route('shopify_accounts.index') }}">店铺管理</a>
                    </dd>

                </dl>
            </li>
        </ul>
    </div>
</div>



