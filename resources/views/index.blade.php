@extends('layouts.app')

@section('content')
    <index
            resource-name="{{$uriKey}}"
            label-name="{{$label}}"
    >
        <el-table-column
                prop="id"
                label="ID"
        >
        </el-table-column>
{{--        @includeWhen($slot, $slotName)--}}

        <el-table-column
                prop="updated_at"
                label="更新时间"
        >
        </el-table-column>
    </index>
@endsection
