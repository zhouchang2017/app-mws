@extends('layouts.app')

@section('content')
    <index
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            can-view
            can-update
            can-destroy
    >
        <el-table-column
                prop="id"
                label="ID"
        >
        </el-table-column>
        <el-table-column
                prop="description"
                label="计划描述"
        >
        </el-table-column>
        <el-table-column
                prop="origin.name"
                label="供应商"
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
        <el-table-column
                prop="updated_at"
                label="更新时间"
        >
        </el-table-column>
    </index>
@endsection
