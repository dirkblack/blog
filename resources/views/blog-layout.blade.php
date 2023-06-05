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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body id="body" class="d-flex flex-column h-100">
<div id="app" class="">
    <main id="main" class="main pt-10">
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col-lg-8 col-md-12 offset-lg-2 blog">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
{{--    <footer id="footer" class="footer d-flex flex-column bg-black px-4 px-lg-7">--}}
{{--        @include('footer')--}}
{{--    </footer>--}}
</div>

</body>
</html>
