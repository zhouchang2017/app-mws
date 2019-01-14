@extends('layouts.guest')

@section('content')
    <div class="mx-auto max-w-sm">
        <div class="py-2 text-center">
            <h1 class="text-center text-lg text-70 my-3">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path class="heroicon-ui" d="M19 10h2a1 1 0 0 1 0 2h-2v2a1 1 0 0 1-2 0v-2h-2a1 1 0 0 1 0-2h2V8a1 1 0 0 1 2 0v2zM9 12A5 5 0 1 1 9 2a5 5 0 0 1 0 10zm0-2a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm8 11a1 1 0 0 1-2 0v-2a3 3 0 0 0-3-3H7a3 3 0 0 0-3 3v2a1 1 0 0 1-2 0v-2a5 5 0 0 1 5-5h5a5 5 0 0 1 5 5v2z"/></svg>
            </h1>
        </div>

        <div class="card">
            <div class="border-b py-8 font-bold text-black text-center text-xl tracking-widest uppercase">
                <p class="font-semibold text-80">{{$message}}</p>
            </div>
        </div>
    </div>

@endsection
