@extends('layouts.app')

@section('content')
    <index resource-name="products" label-name="产品" can-create >
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
    </index>
@endsection
