<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('include.head')
    <title>@yield('titre')</title>
</head>
<body class="index @if(in_array(url()->current(), [route('reclamations.index'), route('pilotage.export')])) reclamations-index @endif">
    <header>
        @include('include.topbar')
    </header>

    <div id="haut_de_page" class="container-fluid">
        <div class="row">
            <div id="content" class="col-12 px-5 py-3 mt-5">
                @yield('headcontent')
                @include('include.message')
                @yield('content')
            </div>
        </div>
    </div>

    <div class="footer bg-dark text-right">
        <p class="m-0 pr-5">
            @include('include.bottombar')
        </p>
    </div>

    @include('include.foot')
    @stack('scripts')
</body>
</html>
