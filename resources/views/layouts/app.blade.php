<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <!-- Scripts -->
    <!-- defer -->
    <!-- script src="{{ asset('js/app.js') }}"></script -->
    <script src="{{ asset('jquery/jquery-3.3.1.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.js')}}"></script>
    @yield('sctipts')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

</head>
<body>
    <div id="app" >
        <nav class="navbar navbar-expand-md navbar-laravel" style="background-color:blue;"   >
            <div class="container" >
              <ol class="breadcrumb" style="background-color:transparent; ">
                <li class="breadcrumb-item"><a href="{{url('/') }}" style="color:white;">S.I.I.</a></li>
                <li class="breadcrumb-item"><a href="{{ route('home') }}" style="color:white;">INICIO</a></li>
                @yield('breadcrumb')
              </ol>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}" style="color:white;">{{ __('INICIAR SESIÓN') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}" style="color:white;" >{{ __('REGISTRARSE') }}</a></li>
                        @else
                            <li class="nav-item dropdown" >
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="color:white;">
                                    {{ Auth::user()->name }}-{{ Auth::user()->rol }}<span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    @switch(Auth::user()->rol)

                                        @case('Coordinador')
                                            <a class="dropdown-item" href="/convocatoria">Convocatorias</a>
                                            <a class="dropdown-item" href="/proyecto/especial">Agregar</a>
                                            <a class="dropdown-item" href="/registrados">Registrados</a>
                                        @break
                                    @endswitch


                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Cerrar sesión') }}
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
