@extends('layouts.app')

@section('content')
    <div>

        <div class="flex items-center">
            <h3 class="text-80 py-3 ml-3">供应商入库计划</h3>
            <div class="ml-auto">
                <form action="{{ route($domain.'.supplies.create') }}">
                    <el-button native-type="submit" type="primary" size="small">创建入库计划</el-button>
                </form>
            </div>
        </div>
        <div class="card p-6 w-full">
            <resources-table resource-name="supplies"></resources-table>
        </div>
    </div>
@endsection
