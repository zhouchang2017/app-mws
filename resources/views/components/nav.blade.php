<el-aside width="200px" class="app-side">
    <el-menu>
        {{--        @dd(\Illuminate\Support\Facades\Route::current()->uri())--}}
        <el-menu-item index="1">
            <i class="el-icon-menu"></i>
            <a href="{{route('supplier.supplies.index')}}">
                <span slot="title">
                入库计划
            </span>
            </a>
        </el-menu-item>
        <el-menu-item index="2">
            <i class="el-icon-document"></i>
            <a href="{{route('supplier.product-variants.index')}}">
                <span slot="title">产品</span>
            </a>
        </el-menu-item>
        <el-menu-item index="3">
            <i class="el-icon-setting"></i>
            <span slot="title">导航四</span>
        </el-menu-item>
    </el-menu>
</el-aside>