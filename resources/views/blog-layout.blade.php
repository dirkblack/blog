<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BlackFox makes your restaurant Cash-Out process faster and more accurate.">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App Name -->
    <title>{{ config('app.name', 'DarkBlack') }}{{ request()->path() == "/" ? ' - Home' : ' - '. ucfirst(request()->path()) }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('/vendor/blog/blog.css') }}" rel="stylesheet">
</head>
<body id="body" class="h-100">
    <div class="flex justify-center">
        <main id="main" class="w-full max-w-screen-lg flex flex-col">
    {{--        Default Navigation / login--}}
            <header id="header" class="w-full relative bg-secondary">
                <nav id="navbar" class="w-full flex justify-end px-4">
                    <ul class="blog_nav_items">
                        <li class="nav-item mr-lg-5 mb-2 mb-lg-0">
                            <a class="nav-link px-0{{ (request()->is('Blog')) ? ' active' : '' }}" href="{{ route('blog') }}">Blog</a>
                        </li>
                        @guest
                            <li class="nav-item mr-lg-5 mb-0">
                                <a class="nav-link px-0" href="{{ route('login') }}">Login</a>
                            </li>
                        @endguest
                        @auth
                            <li class="nav-item dropdown mr-lg-5">
                                <a id="navbarDropdown" class="nav-link px-0 dropdown-toggle text-secondary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="\logout">Log Out</a>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </header>
            <div class="blog bg-gray-50">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
