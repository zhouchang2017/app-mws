<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
</head>
<body class="h-full">
<div id="app" class="text-base text-grey-darkest font-normal relative h-fill">
    <div class="bg-primary">
        <div class="mx-auto w-full px-2 lg:px-4 ">
        <div class="flex sm:items-center justify-between  py-4">
            <div class="w-1/4 flex items-center md:hidden" @click="showNav">
                <svg class="fill-current text-white h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M16.4 9H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1zm0 4H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1zM3.6 7h12.8c.552 0 .6-.447.6-1 0-.553-.048-1-.6-1H3.6c-.552 0-.6.447-.6 1 0 .553.048 1 .6 1z"/></svg>
            </div>
            <div class="w-1/2 md:w-auto text-center text-white text-2xl font-medium">
                <a class="text-white dim no-underline" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            </div>
            <div class="w-1/4   flex items-center text-right">
                <el-dropdown class="ml-auto">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <img class="inline-block h-8 w-8 rounded-full" src="https://avatars0.githubusercontent.com/u/4323180?s=460&v=4" alt="">
                        </div>
                        <div class="hidden md:block md:flex md:items-center ml-2">
                        <span class="text-white text-sm mr-1">
                            @auth
                                {{auth()->user()->name}}
                            @endauth
                        </span>
                        </div>
                    </div>

                    <el-dropdown-menu slot="dropdown">
                        @guest
                            @if (Route::has($domain.'.register'))
                                <el-dropdown-item>
                                    <a class="nav-link" href="{{ route($domain.'.register') }}">{{ __('Register') }}</a>
                                </el-dropdown-item>
                            @endif

                        @else
                            <el-dropdown-item>
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <a class="nav-link" href="{{ route($domain.'.logout') }}"
                                   onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route($domain.'.logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </el-dropdown-item>
                        @endguest
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </div>
    </div>
    </div>
    <div class="flex h-auto  bg-40">
        @component('components.nav-menu')
        @endcomponent
        {{--<div class="container mx-auto lg:p-8 p-4 sm:p-2">--}}
        <div class="flex flex-1 flex-col mx-auto md:p-8 p-4 sm:p-2">
            @yield('content')
        </div>
    </div>


</div>
<script> window.config = @json(\App\Supports\ERP::jsonVariables(request())) </script>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
