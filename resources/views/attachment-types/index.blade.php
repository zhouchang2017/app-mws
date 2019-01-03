@extends('layouts.app')

@section('content')
    <index resource-name="attachment-types" label-name="附加费用类型" can-create >
        <el-table-column
                prop="id"
                label="ID"
        >
        </el-table-column>
        <el-table-column
                prop="name"
                label="费用类型"
        >
        </el-table-column>
        <el-table-column
                prop="rate"
                label="调整比例"
        >
        </el-table-column>
        <el-table-column
                prop="updated_at"
                label="更新时间"
        >
        </el-table-column>
    </index>
@endsection
