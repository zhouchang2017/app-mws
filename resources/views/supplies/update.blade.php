@extends('layouts.app')

@section('content')
    <card-title label="编辑供应商入库计划"></card-title>
    <div class="card w-full">
        <supply-form :origin='@json($resource)'></supply-form>
    </div>
@endsection
