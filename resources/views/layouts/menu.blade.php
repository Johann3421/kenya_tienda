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

                <!-- @can('pedidos')
                <li class="nav-item pcoded-hasmenu">
                    <a href="#" class="nav-link "><span class="pcoded-micon"><i class="fas fa-truck"></i></span><span class="pcoded-mtext">Pedidos</span></a>
                    <ul class="pcoded-submenu">
                        {{-- <li><a href="{{route('pedidos.nuevo')}}">Nuevo Pedido</a></li> --}}
                        <li><a href="{{route('pedidos')}}">Lista de Pedidos</a></li>
                    </ul>
                </li>@endcan -->

                <!-- {{-- <li class="nav-item pcoded-hasmenu">
                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="fas fa-shopping-cart"></i></span><span class="pcoded-mtext">Ventas</span></a>
                    <ul class="pcoded-submenu">
                        <li><a href="{{route('ventas')}}">Mis Ventas</a></li>
                        <li><a href="dashboard-sale.html">Sales</a></li>
                    </ul>
                </li> --}} -->


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

                <!-- @can('proveedores')
                <li class="nav-item"><a href="{{route('proveedores')}}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-truck-loading"></i></span><span class="pcoded-mtext">Proveedores</span></a></li>
                @endcan -->

                <!-- @can('codigo_barras')
                <li class="nav-item"><a href="{{route('barras')}}" class="nav-link "><span class="pcoded-micon"><i class="fas fa-barcode"></i></span><span class="pcoded-mtext">Código de Barras</span></a></li>
                @endcan -->

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
            {{-- <div class="card text-center">
                <div class="card-block">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="feather icon-sunset f-40"></i>
                    <h6 class="mt-3">Help?</h6>
                    <p>Please contact us on our email for need any support</p>
                    <a href="#!" target="_blank" class="btn btn-primary btn-sm text-white m-0">Support</a>
                </div>
            </div> --}}
        </div>
    </div>
</nav>
@section('js')
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
