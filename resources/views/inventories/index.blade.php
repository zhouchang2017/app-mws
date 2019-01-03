@extends('layouts.app')

@section('content')
    <index resource-name="inventories" label-name="库存" >
        <el-table-column
                prop="product_id"
                label="商品id"
        >
        </el-table-column>
        <el-table-column
                prop="variant.variantName"
                label="商品名称"
        >
        </el-table-column>
        <el-table-column
                prop="warehouse.name"
                label="仓库名称"
        >
        </el-table-column>
        <el-table-column
                prop="quantity"
                label="数量"
        >
        </el-table-column>
        <el-table-column
                prop="warehouse_area"
                label="仓库区域"
        >
        </el-table-column>
        <el-table-column
                prop="updated_at"
                label="更新时间"
        >
        </el-table-column>
    </index>
@endsection
