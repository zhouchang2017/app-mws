@extends('layouts.app')

@section('content')
    <div>
        <card-title label="用户中心"></card-title>

        <div class="form-list mb-6">
            <bind-wechat></bind-wechat>
        </div>
    </div>
@endsection
