<!DOCTYPE html>
<html>
<head>
    <base href="{{ url('') }}/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, noodp, NoImageIndex, noydir">
    <title>{{ env('APP_NAME') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link href="{{ asset('lib/bs/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/fa/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('lib/adminlte/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('lib/adminlte/css/skins/skin-blue-light.min.css') }}" rel="stylesheet" type="text/css"/>

    <!--[if lt IE 9]>
    <script src="{{ asset('js/lib/html5shiv.min.js') }}"></script>
    <script src="{{ asset('js/lib/respond.min.js') }}"></script>
    <![endif]-->

    <script src="{{ asset('js/lib/jquery.min.js') }}" type="text/javascript"></script>
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

                    <li class="hidden-sm hidden-xs">
                        <a href="#" id="help" data-route=""
                           data-extra="">
                            <i class="fa fa-question-circle" data-route=""
                               data-extra=""></i>
                        </a>
                    </li>

                    <li>
                        <span style="color:#fff;padding: 15px;display: block;line-height: 20px;">
                            <span id="daterange"></span>
                        </span>
                    </li>

                    <li class="dropdown user user-menu">
                        <span style="cursor:default;color:#fff;padding: 15px;display: block;line-height: 20px;">
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </span>
                    </li>
                    <li id="sidebar-toggle">
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-plus-circle"></i></a>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input autocomplete="off" type="text" name="q" class="form-control"
                           placeholder="Zoeken..." value=""/>
                    <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i
                        class="fa fa-search"></i></button>
              </span>
                </div>
            </form>
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
            <strong>Versie 0.8</strong>
        </div>
    </footer>

</div>
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
</div>

</body>
</html>
