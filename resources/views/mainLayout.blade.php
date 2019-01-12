<!DOCTYPE html>
<html>
<head>
    <base href="{{ url('') }}/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, noodp, NoImageIndex, noydir">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="mobile-web-app-capable" content="yes">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('img/icons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('img/icons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('img/icons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/icons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('img/icons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/icons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('img/icons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('img/icons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/icons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/icons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('img/icons/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ asset('lib/bs/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/fa/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/ion-icons.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('lib/adminlte/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/adminlte/css/skins/skin-blue-light.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/pace.css') }}" rel="stylesheet" type="text/css"/>

    <!--[if lt IE 9]>
    <script src="{{ asset('js/lib/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/lib/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('js/lib/pace.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/ff/moment/en_US.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/lib/daterangepicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('lib/adminlte/js/adminlte.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/lib/chart.js') }}" type="text/javascript"></script>
</head>
<body class="skin-blue-light sidebar-mini hold-transition">
<div class="wrapper" id="app">

    <header class="main-header">

        <a href="{{ url('/') }}" class="logo">
            <span class="logo-mini">AV</span>
            <span class="logo-lg"><b>{{ env('APP_NAME') }}</b></span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Instellingen</a></li>
                            <li><a href="#">Profile</a></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a class="nav-link" href="{{ url('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">Uitloggen</a>
                                <form id="logout-form" action="{{ url('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <!--
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input autocomplete="off" type="text" name="q" class="form-control"
                           placeholder="Zoeken..." value=""/>
                    <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                        class="fa fa-search"></i></button>
              </span>
                </div>
            </form>!-->
            @include('partials.nav')
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">

        </section>

        <section class="content">
            @yield('content')
        </section>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            Versie 0.8 | <a href="mailto:e.douna@gmail.com">Erwin Douna</a>
        </div>
    </footer>

</div>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
</div>

</body>
</html>
