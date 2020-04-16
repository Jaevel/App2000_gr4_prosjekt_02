{{-- Denne er fra før/rett etter at jeg, Ross, la til nedtrekks liste! --}}

<!doctype html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('name', 'Samkjøring AS') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="{{URL::to('/')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{URL::to('/')}}/css/heroic-features.css" rel="stylesheet">

</head>
<body>
    <div id="app">

      <!-- Navigation -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
          <a class="navbar-brand" href="{{ route('index') }}">Samkjøring AS</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ Request::path() === '/' ? 'active' : '' }}">

                <a class="nav-link" href="{{ route('index') }}">{{ __('Home') }}
                  <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item {{ Request::path() === 'varslinger' ? 'active' : '' }}">
                <a class="nav-link" href="/varslinger">{{ __('Notifications') }}</a>
              </li>
              <li class="nav-item {{ Request::path() === 'omoss' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('about') }}">{{ __('About Us') }}</a>
              </li>
              @if (Route::has('login'))
                  @auth
                  <li class="nav-item {{ Request::path() === 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('home') }}">{{ __('Profile') }}</a>
                  </li>
                  <li>
                     <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                  </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  @else
                  <li>
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                  </li>
                  @endauth

                  <li>
                    {{-- Endre dette til enten "flagg ikon" eller "språk navn" --}}
                    <a class="nav-link" href="{{ url('locale/no') }}">{{ __('No') }}</a>
                    <a class="nav-link" href="{{ url('locale/en') }}">{{ __('En') }}</a>
                  </li>

              @endif

              @auth
                <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }} <span class="caret"></span>
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
              @endauth

            </ul>

          </div>
        </div>
      </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Grp04 2020</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="{{URL::to('/')}}vendor/jquery/jquery.min.js"></script>
    <script src="{{URL::to('/')}}vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>