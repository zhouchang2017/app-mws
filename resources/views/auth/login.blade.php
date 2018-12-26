@extends('layouts.guest')

@section('content')
    <div class="h-full flex  justify-center items-center w-full max-w-xs flex-col m-auto">
        <h1 class="text-center text-70 my-3">{{config('app.name')}}</h1>
        <form class="bg-white shadow-lg rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email"
                       class="block text-grey-darker text-sm font-bold mb-2 text-80">{{ __('E-Mail Address') }}</label>

                <input id="email" type="email"
                       class=" appearance-none border rounded w-full py-2 px-3 text-grey-darker leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('email') ? ' border-red' : '' }}"
                       name="email" value="{{ old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <p class="text-danger text-xs italic" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </p>
                @endif
            </div>

            <div class="mb-6">
                <label for="password"
                       class="block text-grey-darker text-sm font-bold mb-2 text-80">{{ __('Password') }}</label>

                <div class="col-md-8">
                    <input id="password" type="password"
                           class=" appearance-none border  rounded w-full py-2 px-3 text-grey-darker mb-3 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('password') ? ' border-red' : '' }}"
                           name="password" required>

                    @if ($errors->has('password'))
                        <p class="text-red text-xs italic" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </p>
                    @endif
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center">
                    <input class="mr-3" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="text-80" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">
                    {{ __('Login') }}
                </button>
                @if (Route::has('password.request'))
                    <a class="inline-block align-baseline font-bold text-sm text-primary-50% hover:text-blue-darker"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>

        </form>

    </div>
@endsection
