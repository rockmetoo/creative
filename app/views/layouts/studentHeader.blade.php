<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Schooler - Learn and teach anywhere</title>

        @if (App::environment('production'))
          @include('layouts.productionHeader')
        @else
          @include('layouts.header')
        @endif

        @yield('internalCSSLibrary')

        @yield('internalJSLibrary')
        @yield('internalJSCode')
    </head>
    <body>
        <div id="wrapper">
            <nav class="navbar navbar-default navbar-fixed-top bg-white" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/" class="navbar-brand">
                        <span class="text-danger">Schlooer</span>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a class="dropdown-toggle" href="/profile">{{ Auth::user()->email }}</a>
                    </li>
                    <li>
                        <a class="dropdown-toggle" href="/signout">Signout</a>
                    </li>
                </ul>
                <div class="navbar-default sidebar" role="navigation" style="width:194px;position:fixed">
                    <div class="sidebar-nav navbar-collapse">
                        <div class="user-block clearfix">
                            @yield('leftSideUserBlock')
                        </div>
                        <ul class="nav" id="side-menu" style="width:100%">
                            @yield('leftSideMenu')
                        </ul>
                    </div>
                </div>
            </nav>
            @yield('content')
        </div>
    </body>
@if (App::environment('production'))
    @include('elements.productionjs')
@else
    @include('elements.js')
@endif
</html>
