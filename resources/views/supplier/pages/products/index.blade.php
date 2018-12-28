@extends('layouts.app')

@section('content')
    <h1 class="mb-3 text-90 font-normal text-2xl">产品</h1>
    <resources-table label-name="创建产品" resource-name="products">
        <el-table-column
                prop="code"
                label="产品编码"
        >
        </el-table-column>
        <el-table-column
                prop="name"
                label="产品名称"
        >
        </el-table-column>
        <el-table-column
                prop="check_state"
                label="状态"
        >
        </el-table-column>
    </resources-table>
@endsection
