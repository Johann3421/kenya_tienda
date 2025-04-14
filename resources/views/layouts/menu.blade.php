<nav class="pcoded-navbar menupos-fixed menu-light " style="padding-bottom: 50px;">
    <div class="navbar-wrapper  ">
        <div class="navbar-content scroll-div ">
            <ul class="nav pcoded-inner-navbar" style="margin-bottom: 70px;">
                <li class="nav-item pcoded-menu-caption">
                    <label>Menú de Navegación</label>
                </li>

                @can('inicio')
                <li class="nav-item">
                    <a href="{{route('home')}}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="pcoded-mtext">Inicio</span>
                    </a>
                </li>
                @endcan

                @can('servicio_tecnico')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-tools"></i></span><span class="pcoded-mtext">Servicio Técnico</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{route('soporte')}}">Soporte Técnico</a></li>
                    </ul>
                </li>
                @endcan

                @can('pedidos')
                <li class="nav-item"><a href="{{route('pedidos')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-truck"></i></span>
                    <span class="pcoded-mtext">Pedidos</span></a>
                </li>
                @endcan

                @can('productos')
                <li class="nav-item"><a href="{{route('productos')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-archive"></i></span>
                    <span class="pcoded-mtext">Productos</span></a>
                </li>
                @endcan

                @can('producto_drivers')
                <li class="nav-item"><a href="{{route('producto/drivers')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-solid fa-screwdriver"></i></span>
                    <span class="pcoded-mtext">Drivers</span></a>
                </li>
                @endcan

                @can('producto_drivers_ruta')
                <li class="nav-item"><a href="{{route('producto/drivers_ruta')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fa fa-book" aria-hidden="true"></i></span>
                    <span class="pcoded-mtext">Links</span></a>
                </li>
                @endcan

                @can('manual')
                <li class="nav-item"><a href="{{route('producto/manuales')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-regular fa-newspaper"></i></span>
                    <span class="pcoded-mtext">Manuales</span></a>
                </li>
                @endcan

                @can('garantia')
                <li class="nav-item"><a href="{{route('producto/garantias')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-solid fa-stamp"></i></span>
                    <span class="pcoded-mtext">Soporte</span></a>
                </li>
                @endcan

                @can('clientes')
                <li class="nav-item"><a href="{{route('clientes')}}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-users"></i></span><span class="pcoded-mtext">Clientes</span></a></li>
                @endcan

                @can('categorias')
                <li class="nav-item"><a href="{{route('producto/categorias')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-solid fa-folder"></i></span>
                    <span class="pcoded-mtext">Categorias</span></a>
                </li>
                @endcan

                @can('modelos')
                <li class="nav-item"><a href="{{route('producto/modelos')}}" class="nav-link ">
                    <span class="pcoded-micon"><i class="fas fa-solid fa-folder-open"></i></span>
                    <span class="pcoded-mtext">Modelos</span></a>
                </li>
                @endcan
                @can('filtros')
<li class="nav-item {{ request()->routeIs('sistema.aside.*') ? 'active' : '' }}">
    <a href="{{ route('sistema.aside.index') }}" class="nav-link">
        <span class="pcoded-micon"><i class="fas fa-sliders-h"></i></span>
        <span class="pcoded-mtext">Filtros Avanzados</span>
        @if(request()->routeIs('sistema.aside.*'))
            <span class="pcoded-badge label label-danger">ACTIVO</span>
        @endif
    </a>
</li>
@endcan

                @if( auth()->user()->can('perfiles') || auth()->user()->can('usuarios') || auth()->user()->can('pagina_web') || auth()->user()->can('configuracion'))
                    <li class="nav-item pcoded-menu-caption">
                        <label>Administrador</label>
                    </li>
                @endif

                @can('perfiles')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-address-card"></i></span><span class="pcoded-mtext">Perfiles</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{route('permisos')}}">Permisos</a></li>
                        <li><a href="{{route('roles')}}">Roles</a></li>
                    </ul>
                </li>
                @endcan

                @can('usuarios')
                <li class="nav-item"><a href="{{route('usuarios')}}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-user-shield"></i></span><span class="pcoded-mtext">Usuarios</span></a></li>
                @endcan

                @can('pagina_web')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-tv"></i></span><span class="pcoded-mtext">Página Web</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{route('banners')}}">Banners</a></li>
                    </ul>
                </li>
                @endcan

                @can('configuracion')
                <li class="nav-item"><a href="{{route('configuracion')}}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-cog"></i></span><span class="pcoded-mtext">Configuración</span></a></li>
                @endcan
            </ul>
        </div>
    </div>
</nav>
@section('js')
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
