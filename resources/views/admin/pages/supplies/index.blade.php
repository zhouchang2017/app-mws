@extends('layouts.app')

@section('content')
    <h1 class="mb-3 text-90 font-normal text-2xl">供应商入库计划</h1>
    <resources-table :can-create="false" label-name="创建入库计划" resource-name="supplies">
        <el-table-column
                prop="description"
                label="计划描述"
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
