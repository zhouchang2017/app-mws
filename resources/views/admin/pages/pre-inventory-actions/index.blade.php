@extends('layouts.app')

@section('content')
    <card-title label-name="预出\入库(入库单\出货单)"></card-title>
    <resources-table :can-create="false"  :can-update="false"  label-name="创建预出\入库(入库单\出货单)" resource-name="pre-inventory-actions">
        <el-table-column
                prop="description"
                label="计划描述"
        >
        </el-table-column>
        <el-table-column
                prop="type.name"
                label="类型"
        >
        </el-table-column>
        <el-table-column
                prop="current_state"
                label="当前状态"
        >
        </el-table-column>
        <el-table-column
                prop="updated_at"
                label="最后更新时间"
        >
        </el-table-column>
    </resources-table>
@endsection
