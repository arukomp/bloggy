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
    <link href="{{ bloggy_asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            <span class="navbar-text navbar-right">
                Welcome, <strong>{{ Auth::user()->name }}</strong>
            </span>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        Sign Out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <div class="container-fluid">
            <div class="row">
                
                @include('bloggy::layouts.admin.partials.sidebar')

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4" style="margin-bottom: 70px;">
                    @include('bloggy::layouts.admin.partials.flash-messages')

                    @yield('content')
                </main>

                <nav class="navbar fixed-bottom navbar-light bg-light">
                    @if (defined('LARAVEL_START'))
                    <span class="navbar-text">
                        Page generated in <strong>{{ sprintf("%f", microtime(1) - LARAVEL_START) }} seconds</strong>
                    </span>
                    @endif
                    <span class="navbar-text float-right">
                        <span class="float-right">Made by <strong><a href="https://arukomp.com">ARUKOMP</a></strong></span>
                    </span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>feather.replace();</script>
    <script src="{{ bloggy_asset('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>
