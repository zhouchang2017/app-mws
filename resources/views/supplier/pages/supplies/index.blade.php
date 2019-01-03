@extends('layouts.app')

@section('content')
    <index resource-name="supplies" label-name="入库计划" can-create >
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
    </index>
@endsection
