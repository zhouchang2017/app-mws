@extends('layouts.app')

@section('content')
    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"
            :can-update='@json($resource->authorize['canUpdate'])'
            :can-destroy='@json($resource->authorize['canDestroy'])'
    ></resource-detail-header>

    <div class="form-list mb-6">
        <form-item title="名称" value="{{$resource->name}}"></form-item>
        <form-item title="编码" value="{{$resource->code}}"></form-item>
        <form-item title="描述" value="{{$resource->description }}"></form-item>
        <form-item title="余额" value="{{$resource->banlance }}"></form-item>
        <form-item title="店长" value="{{$resource->manager->name }}" uri-key="supplier-users"
                   resource-id="{{$resource->manager->id}}"></form-item>
        <form-item title="官方小二" value="{{$resource->admin->name }}" uri-key="users"
                   resource-id="{{$resource->admin->id}}"></form-item>
        <form-item title="更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>

    <resource-index-header
            via-relation-name="supplier"
            uri-key="supplier-users"
            via-relation-id="{{$resource->id}}"
            singular-label="用户"
            can-create
            label="用户"
    ></resource-index-header>
    <div class="form-list mb-6">
        @if($resource->users()->count() === 0)
            <empty-resources>
                <h3 class="text-base text-80 font-normal mb-6">
                    暂无用户
                </h3>
                <a href="{{route($domain.'.supplier-users.create').'?viaRelationName=supplier&viaRelationId='.$resource->id}}"
                   class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">
                    创建用户
                </a>
            </empty-resources>
        @else
            <el-table
                    :data='@json($resource->users)'
            >
                <el-table-column
                        prop="email"
                        label="Email"
                >
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="Name"
                >
                </el-table-column>
                <el-table-column
                        prop="phone"
                        label="手机"
                >
                </el-table-column>

                <el-table-column
                        align="right"
                        fixed="right"
                        label="操作"
                >
                    <template slot-scope="{row}">
                        <a class="cursor-pointer text-70 hover:text-primary mr-3"
                           :href="`/supplier-users/${row.id}`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                        </a>
                        <a class="cursor-pointer text-70 hover:text-primary"
                           :href="`/supplier-users/${row.id}/edit`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/>
                            </svg>
                        </a>
                    </template>
                </el-table-column>
            </el-table>
        @endif
    </div>

    <detail-address :address='@json($resource->officeAddress)' api="{{route($domain.'.suppliers.address.store',['supplier'=>$resource])}}" label="办公地址"
                    collection-name="office"></detail-address>

    <detail-address :address='@json($resource->warehouseAddress)' api="{{route($domain.'.suppliers.address.store',['supplier'=>$resource])}}" label="仓库地址"
                    collection-name="warehouse"></detail-address>

@endsection