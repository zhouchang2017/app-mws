@extends('layouts.app')

@section('content')
    <index resource-name="product-variants" label-name="变体" >
        <el-table-column
                prop="code"
                label="商品编码"
        >
        </el-table-column>
        <el-table-column
                prop="variantName"
                label="商品名称"
        >
        </el-table-column>
    </index>
@endsection
