@extends('layouts.app')

@section('content')
    <card-title label-name="编辑供应商入库计划"></card-title>
    <div class="card p-6 w-full">
        <supply-form :origin='@json($resource)'></supply-form>
    </div>
@endsection
