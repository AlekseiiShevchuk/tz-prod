<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->


    <link rel="stylesheet" href="{{ url('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ url('/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}" />
    @stack('css')


    <!-- Scripts -->

    <script type="text/javascript" src="{{ url('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/bower_components/moment/min/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!}
    </script>

    <style>
        .pagination.per_page{
            float: right;
        }
        .empty_result{
            text-align: center;
            font-size: 20px;
        }
        table th.three-actions{
            min-width: 100px;
        }
    </style>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if(Auth::check())
                        @if (Auth::user()->isAdmin())
                            <li><a href="{{ url('/ap/users?role=client') }}">Clients</a></li>
                            <li><a href="{{ url('/ap/users?role=partner') }}">Partners</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Audio <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ url('/ap/categories') }}">Categories</a></li>
                                    <li><a href="{{ url('/ap/groups') }}">Groups</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="{{ url('/ap/sounds') }}">Sounds</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ url('/ap/report') }}">Reporting</a></li>
                        @endif

                        @if (Auth::user()->isPartner())
                                <li><a href="{{ url('/ap/report') }}">Reporting</a></li>
                        @endif
                    @endif

                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} {{ Auth::user()->surname }}<span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script type="text/javascript" src="{{ url('/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
@stack('scripts')

{{--<script src="/js/app.js"></script>--}}

<script>
    $(document).ready(function(){
        $('[onlynumbers="true"]').on('keydown', function(e){
            if(e.code != 'Backspace' && e.key != 'Backspace'){
                if(!/^[+()\d-]+$/.test(e.key)){
                    e.preventDefault();
                }
            }
        });

        $('[datetimepicker="true"]').datetimepicker({
            format: '{{ trans('app.date_format') }}'
        });
    });
</script>

</body>
</html>
