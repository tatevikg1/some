<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="/svg/wings.svg"/>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel fixed-top">
            <div class="container" >
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="padding:0px">
                    <div><img src="/svg/wings0.svg" style="height: 30px; border-right: 1px solid #aaa;" class="pr-3"></div>
                    <div class="pl-3 app_name">Wings</div>
                </a>
                @auth
                <form class="d-flex mr-3" action="/profile/find" method="post">
                    @csrf
                    <input type="text" name="username" placeholder="Search" style="padding-right: 50px;">
                    <input type="submit"  value="Ok" style="margin-left: -47px;">
                </form>
                @endauth

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @guest
                        @else
                        <li class="nav-item">
                            <a class="dropdown-item white" href="{{ route('profile.show',  Auth::user()->id) }} "> {{ __('My Profile') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item white" href="{{ route('profile.index') }}"> {{ __('All Users') }} </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item white" href="{{ route('post.index') }}"> {{ __('Posts') }} </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item white" href="{{ route('post.create') }}">  Add Post </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item white" href="{{ route('chat') }}"> Chat </a>
                        </li>
                        @endguest
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}" style="color:white">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}" style="color:white">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" style="color: white;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                     <span class="caret" > {{ Auth::user()->name }} </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div style="margin-top:10px">
            <main class="py-4 mt-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
