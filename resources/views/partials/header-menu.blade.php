<nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
    <ul class="kenya-nav-list">
        <li class="{{ (request()->is('/') || request()->routeIs('inicio')) ? 'kenya-active' : '' }}">
            <a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a>
        </li>
        <li class="{{ request()->routeIs('quienes.somos') ? 'kenya-active' : '' }}">
            <a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a>
        </li>
        <li class="{{ (request()->routeIs('catalogo*') || request()->is('Catalogo*') || request()->is('producto*')) ? 'kenya-active' : '' }}">
            <a href="{{ route('catalogo') }}" class="kenya-nav-link kenya-nav-perucompras" title="Catálogo Perú Compras" aria-label="Catálogo Perú Compras">
                <img src="{{ asset('logo_PeruCompras.svg') }}" alt="Perú Compras" class="kenya-perucompras-logo">
            </a>
        </li>
        <li class="{{ request()->routeIs('novedades') ? 'kenya-active' : '' }}">
            <a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a>
        </li>
        <li class="{{ (request()->is('consultar/*') || request()->routeIs('consultar.garantia*')) ? 'kenya-active' : '' }}">
            <a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a>
        </li>
        <li class="{{ request()->routeIs('contactenos') ? 'kenya-active' : '' }}">
            <a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a>
        </li>
    </ul>
</nav>
