<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/vendor.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="h-screen w-full bg-40 text-black  font-sans">
    <el-container class="h-full">
        <el-header class="bg-indigo-darker text-center p-4 px-6 flex items-center text-white flex">
            <div>
                <a class="h-2 font-bold text-white" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
            <el-dropdown class="ml-auto">
                <i class="el-icon-setting text-white mr-3"></i>
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
            @auth
                <div><span>{{auth()->user()->name}}</span></div>
            @endauth
        </el-header>

        <el-container>
            @component('components.nav2')
            @endcomponent
            <el-main>
                @yield('content')
            </el-main>
        </el-container>
    </el-container>

</div>
<script> window.config = @json(\App\Supports\ERP::jsonVariables(request())) </script>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
