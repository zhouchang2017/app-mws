@extends('layouts.app')

@section('content')
    <index
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            :can-create="@json($canCreate)"
            :can-search="@json($canSearch)"
            :can-view="@json($canView)"
            :can-update="@json($canUpdate)"
            :can-destroy="@json($canDestroy)"
    >
        <el-table-column
                prop="id"
                label="ID"
        >
        </el-table-column>
        @includeIf($uriKey.'.index')
        <el-table-column
                prop="updated_at"
                label="更新时间"
        >
        </el-table-column>
    </index>
@endsection
