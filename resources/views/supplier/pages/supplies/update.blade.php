@extends('layouts.app')

@section('content')
    <div>

        <div class="flex items-center">
            <h3 class="text-80 py-3 ml-3">编辑供应商入库计划</h3>
        </div>
        <div class="card p-6 w-full">
            <supply-form></supply-form>
        </div>
    </div>
@endsection
