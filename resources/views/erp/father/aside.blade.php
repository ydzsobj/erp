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
                        <a lay-href="{{url('/admins/product')}}">订单列表</a>
                    </dd>

                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="仓库管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>仓库管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/storage')}}">仓库列表</a>
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
                        <a lay-href="{{url('/admins/product')}}">采购列表</a>
                    </dd>

                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="供应商管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>供应商管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/supplier')}}">供应商列表</a>
                    </dd>

                </dl>
            </li>
            <li data-name="home" class="layui-nav-item ">
                <a href="javascript:;" lay-tips="财务管理" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>财务管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{url('/admins/product')}}">财务列表</a>
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
                        <a lay-href="{{url('/admins/product')}}">物流列表</a>
                    </dd>

                </dl>
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
                <a href="javascript:;" lay-tips="辅助工具" lay-direction="2">
                    <i class="layui-icon layui-icon-util"></i>
                    <cite>辅助工具</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="http://baidu.com">Baidu</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="/admins/jsq">科学计算器</a>
                    </dd>
                </dl>
            </li>
        </ul>
    </div>
</div>



