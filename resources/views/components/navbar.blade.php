<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse ms-2" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ route('homepage') }}">
                        <img src="/image/steering-wheel.png" class="logo" alt="">
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link active" aria-current="page" href="{{ route('article.index') }}">Tutti gli
                        articoli</a>
                </li>
                
            </ul>
{{-- Area riservata --}}
            @auth
                <ul class="nav-item dropdown mt-2">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Ciao {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#"
                                onclick="event.preventDefault(); document.querySelector('#form-logout').submit();">Logout</a>
                        </li>
                        <form action="{{ route('logout') }}" method="POST" id="form-logout" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </ul>
                <a class="nav-link ms-2 mb-2 " href="{{ route('article.create') }}">Inserisci un articolo</a>
                @if (Auth::user()->is_revisor)
                <li><a class="dropdown-item" href="{{route('revisor.dashboard')}}">Dashboard Revisor</a></li>
            @endauth
            @guest
                <ul class="nav-item dropdown mt-2 ps-2">
                    <a class="nav-link " href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><img
                            src="image/racer.png" class="logo" alt=""></a>
                    <ul class="dropdown-menu ">
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">Registrati</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">Accedi</a>
                        </li>
                        @if (Auth::user()?->is_admin) {{-- AGGIUNTO IL ? (operatore nullsafe) ALTRIMENTI NON FUNZIONAVA--}}
                        
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        </li>
                        @endif
                    </ul>
                </ul>
            @endguest


{{-- CONTINUO NAVBA --}}
            <form class="d-flex me-2" role="search">
                <input class="form-control pe-2 ms-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn ms-1" type="submit"><img src="/image/lente.png" class="logo1" alt=""></button>
            </form>
        </div>
    </div>
</nav>
