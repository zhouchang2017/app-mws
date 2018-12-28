@extends('layouts.app')

@section('content')
    <h1 class="mb-3 text-90 font-normal text-2xl">SKU</h1>
    <resources-table label-name="创建变体" resource-name="product-variants">
        <el-table-column
                prop="code"
                label="产品编码"
        >
        </el-table-column>
        <el-table-column
                prop="variantName"
                label="SKU名称"
        >
        </el-table-column>
        <el-table-column
                prop="stock"
                label="库存"
        >
        </el-table-column>
    </resources-table>
@endsection
