@extends('layouts.app')

@section('content')
    <div>
        <h2 class="text-80 pb-3">用户中心</h2>
        <div class="card p-6 w-full">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
            </div>
        </div>
    </div>
@endsection
