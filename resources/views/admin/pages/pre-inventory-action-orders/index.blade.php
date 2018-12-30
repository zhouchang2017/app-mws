@extends('layouts.app')

@section('content')
    <card-title label-name="操作单"></card-title>
    <resources-table :can-create="false" :can-create="false" label-name="操作单" resource-name="pre-inventory-action-orders">
        <el-table-column
                prop="description"
                label="计划描述"
        >
        </el-table-column>
        <el-table-column
                prop="warehouse.name"
                label="仓库"
        >
        </el-table-column>
        <el-table-column
                prop="type.name"
                label="类型"
        >
        </el-table-column>
        <el-table-column
                prop="updated_at"
                label="最后更新时间"
        >
        </el-table-column>
    </resources-table>
@endsection
