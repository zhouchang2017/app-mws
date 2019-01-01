@extends('layouts.app')

@section('content')
    <card-title label-name="产品"></card-title>
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
