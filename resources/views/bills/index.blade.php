@extends('layouts.app')

@section('content')
    <index
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            :can-create="@json($canCreate)"
            :can-search="@json($canSearch)"
            :can-view="@json($canView)"
            :can-update="@json($canUpdate)"
            :can-destroy="@json($canDestroy)"
    >
        <cash-panel slot="indexHeader"></cash-panel>
        @admin
        <el-table-column
                prop="id"
                label="ID"
        >
        </el-table-column>
        @endadmin
        <el-table-column
                prop="number"
                label="账单号"
        >
        </el-table-column>
        <el-table-column
                prop="product.name"
                label="产品名称"
        >
        </el-table-column>
        <el-table-column
                prop="variant.name"
                label="商品名称"
        >
        </el-table-column>

        <el-table-column
                prop="supplier.name"
                label="供应商名称"
        >
        </el-table-column>

        <el-table-column
                prop="quantity"
                label="数量"
        >
        </el-table-column>

        <el-table-column
                prop="origin_price"
                label="商品售价"
        >
        </el-table-column>

        <el-table-column
                prop="price"
                label="实收"
        >
        </el-table-column>
        <el-table-column
                prop="current_state"
                label="状态"
        >
        </el-table-column>
        {{--<el-table-column--}}
        {{--prop="updated_at"--}}
        {{--label="更新时间"--}}
        {{-->--}}
        {{--</el-table-column>--}}
    </index>
@endsection
