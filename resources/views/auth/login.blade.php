@extends('layouts.guest')

@section('content')
    <div class="mx-auto max-w-sm">
        <div class="py-10 text-center">
            <h1 class="text-center text-70 my-3">{{config('app.name')}}</h1>
        </div>

        <div class="bg-white rounded shadow">
            <div class="border-b py-8 font-bold text-black text-center text-xl tracking-widest uppercase">
                Welcome back!
            </div>

            <form class="bg-grey-lightest px-10 py-10" method="POST" action="{{ route($domain.'.login') }}">
                @csrf

                <div class="mb-3">
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
                </div>
            </form>

            <div class="border-t px-10 py-6">
                <div class="flex">
                    @if (Route::has($domain.'.password.request'))
                        <a class="ml-auto inline-flex font-bold text-primary hover:text-primary-50% no-underline"
                           href="{{ route($domain.'.password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
