<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="text-base text-grey-darkest font-normal relative bg-40 min-h-fill">
    <div class="flex flex-col">
        @if(Route::has($domain.'.login'))
            <div class="absolute pin-t pin-r mt-4 mr-4">
                @auth
                    <a href="{{ url('/home') }}" class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase">{{ __('Home') }}</a>
                @else
                    <a href="{{ url('/login') }}" class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase pr-6">{{ __('Login') }}</a>
                    <a href="{{ url('/register') }}" class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase">{{ __('Register') }}</a>
                @endauth
            </div>
        @endif

        <div class="min-h-screen flex items-center justify-center">
            <div class="flex flex-col justify-around h-full">
                <div>
                    <h1 class="text-grey-darker text-center font-thin tracking-wide text-5xl mb-6">
                        {{ config('app.name', 'WE-WEE') }}
                    </h1>
                    <ul class="list-reset">
                        <li class="inline pr-8">
                            <a href="http://admin.penseng.eienao.com/home"
                               class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase"
                               title="Admin">Admin</a>
                        </li>
                        <li class="inline pr-8">
                            <a href="http://supplier.penseng.eienao.com/home" class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase"
                               title="Supplier">Supplier</a>
                        </li>
                        <li class="inline pr-8">
                            <a href="https://pensong.com" class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase"
                               title="Pensong">Pensong</a>
                        </li>
                        <li class="inline pr-8">
                            <a
                               class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase" title="Nova">App</a>
                        </li>
                        <li class="inline pr-8">
                            <a  class="no-underline hover:underline text-sm font-normal text-teal-darker uppercase"
                               title="Forge">E-commerce</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
