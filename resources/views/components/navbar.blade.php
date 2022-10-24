<nav class="navbar navbar-expand-lg fixed-top bg-light shadow-sm">
    <div class="container-fluid">
        <div>
            <a href="{{url('/')}}" class="me-1"><img src="{{asset("images/logo_3d.svg")}}" width="30" alt=""/></a>
            <a href="{{url('/')}}"><img src="{{asset("images/logo_sph.svg")}}" width="30" alt=""/></a>
        </div>
        <a class="navbar-brand ms-2" href="{{ url('/') }}">
            {{ config('app.name')}}
            @if(env('APP_STATUS') === 'dev')
                <span class="border border-2 badge border-success ms-2 text-black bg-success bg-opacity-25 border-opacity-50">Dev</span>
            @endif
            @if(env('APP_STATUS') === 'alpha')
                <span class="border border-2 badge border-danger mx-2 text-black bg-danger bg-opacity-25 border-opacity-50">Alpha</span>
            @endif
            @if(env('APP_STATUS') === 'beta')
                <span class="border border-2 badge border-primary mx-2 text-black bg-primary bg-opacity-25 border-opacity-50">Beta</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Center Of Navbar -->

            @if(str_contains(str_replace('/',' ',request()->route()->uri),'commesse'))
                <div class="d-flex ms-auto">
                    <button class="btn btn-outline-primary me-2" onclick="window.location.href = window.location.href.split('?')[0]">
                        <i class="bi bi-eraser"></i>
                    </button>
                    <form class="d-flex" role="search" method="get" action="/commesse">

                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search" name="search" id="search" value="{{ old('search') }}"
                               required>
                        <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>

            @endif

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Accedi') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Registrati') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->surname }} {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left me-2"></i>{{ __('Logout') }}
                            </a>
                            <a class="dropdown-item" href="{{ url('/change-password') }}" >
                                <i class="bi bi-key me-2"></i>Cambia password
                            </a>
                            <a class="dropdown-item" href="{{ url('/www/index.php') }}" >
                                <i class="bi bi-archive me-2"></i>Archivio
                            </a>
                            <a class="dropdown-item" href="{{ route('bug.report.index') }}" >
                                <i class="bi bi-bug me-2"></i>Bug report
                            </a>
                            @role('boss|admin')
                                <a class="dropdown-item" href="{{ url('/access-keys') }}" >
                                    <i class="bi bi-door-open me-2"></i>Chiavi di accesso
                                </a>
                            @endrole

                            @role('developer')
                                <a class="dropdown-item" href="{{ url('/maileclipse') }}" >
                                    <i class="bi bi-envelope-check me-2"></i>Gestione mail
                                </a>
                            @endrole

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
                @if(isset($require_navbar_tools))
                    <div class="navbar-nav ms-auto">
                        <a onclick="history.go(-1);"><i class="bi bi-arrow-counterclockwise fs-3 me-2 text-primary"></i></a>
                        <a href="/{{explode("/",request()->route()->uri)[0]}}/create"><i
                                class="bi bi-plus-square fs-3 me-2 text-success"></i></a>
                    </div>
                @endif
            </ul>
        </div>
    </div>
</nav>
