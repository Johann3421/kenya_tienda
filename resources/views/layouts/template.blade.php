<!DOCTYPE html>
<html lang="es">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('app-name')

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="icon" href="{{asset('theme/images/favicon.ico')}}" type="image/x-icon">

        <link rel="stylesheet" href="{{asset('theme/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('theme/css/custom.css')}}">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Cargar Bootstrap JS (opcional pero recomendado si usas modales de Bootstrap) -->


    <!-- Toastr para notificaciones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        @yield('css')
        <style>
            .modal-content {
                background-color: #fff7f1;
            }
        </style>
    </head>
    <body class="">
        <div class="loader-bg">
            <div class="loader-track">
                <div class="loader-fill"></div>
            </div>
        </div>

        @include('layouts.menu')

        <header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed" style="padding: 0px;">
            <div class="m-header">
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
                <a href="{{url('/')}}" class="b-brand">
                    @php
                        $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                    @endphp
                    @if ($logo_sistema->archivo)
                        <img src="{{asset('storage/'.$logo_sistema->archivo_ruta.'/'.$logo_sistema->archivo)}}" alt="" class="logo" style="height: 50px;">
                    @else
                        <img src="{{asset('theme/images/kenya.png')}}" alt="kenya" class="logo" style="width: 76%;">
                    @endif
                    {{-- <img src="{{asset('theme/images/logo-icon.png')}}" alt="" class="logo-thumb"> --}}
                </a>
                <a href="#!" class="mob-toggler">
                    <i class="feather icon-more-vertical"></i>
                </a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
                        <div class="search-bar">
                            <input type="text" class="form-control border-0 shadow-none" placeholder="Search here">
                            <button type="button" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    
                    <li>
                        <div class="dropdown drp-user triangle">
                            <a href="#!" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{asset('theme/images/auth/auth.jpg')}}" class="img-radius wid-40" alt="User-Profile-Image">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-notification">
                                <div class="pro-head">
                                    <span>{{ Auth::user()->nombres.' '.Auth::user()->ape_paterno.' '.Auth::user()->ape_materno }}</span>
                                </div>
                                <ul class="pro-body">
                                    <li><a href="{{ route('perfil') }}" class="dropdown-item"><i class="feather icon-user"></i> Perfil</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                        class="dropdown-item"><i class="feather icon-log-out"></i> Cerrar Sesión</a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </header>



        <section class="header-chat">
            <div class="h-list-header">
                <h6>Josephin Doe</h6>
                <a href="#!" class="h-back-user-list"><i class="feather icon-chevron-left"></i></a>
            </div>
            <div class="h-list-body">
                <div class="main-chat-cont scroll-div">
                    <div class="main-friend-chat">
                        <div class="media chat-messages">
                            {{-- <a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="{{asset('theme/images/user/avatar-2.jpg')}}" alt="Generic placeholder image"></a> --}}
                            <div class="media-body chat-menu-content">
                                <div class="">
                                    <p class="chat-cont">hello tell me something</p>
                                    <p class="chat-cont">about yourself?</p>
                                </div>
                                <p class="chat-time">8:20 a.m.</p>
                            </div>
                        </div>
                        <div class="media chat-messages">
                            <div class="media-body chat-menu-reply">
                                <div class="">
                                    <p class="chat-cont">Ohh! very nice</p>
                                </div>
                                <p class="chat-time">8:22 a.m.</p>
                            </div>
                            {{-- <a class="media-right photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="{{asset('theme/images/user/avatar-1.jpg')}}" alt="Generic placeholder image"></a> --}}
                        </div>
                        <div class="media chat-messages">
                            {{-- <a class="media-left photo-table" href="#!"><img class="media-object img-radius img-radius m-t-5" src="{{asset('theme/images/user/avatar-2.jpg')}}" alt="Generic placeholder image"></a> --}}
                            <div class="media-body chat-menu-content">
                                <div class="">
                                    <p class="chat-cont">can you help me?</p>
                                </div>
                                <p class="chat-time">8:20 a.m.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-list-footer">
                <div class="input-group">
                    <input type="file" class="chat-attach" style="display:none">
                    <a href="#!" class="input-group-prepend btn btn-success btn-attach">
                        <i class="feather icon-paperclip"></i>
                    </a>
                    <input type="text" name="h-chat-text" class="form-control h-auto h-send-chat" placeholder="Write hear . . ">
                    <button type="submit" class="input-group-append btn-send btn btn-primary">
                        <i class="feather icon-message-circle"></i>
                    </button>
                </div>
            </div>
        </section>


        <div class="pcoded-main-container">
            <div class="pcoded-content">
                <!-- CONTENIDO -->
                @yield('content')
                <!-- Bootstrap JS Bundle con Popper -->

            </div>
        </div>

        <script src="{{asset('theme/js/vendor-all.min.js')}}"></script>
        <script src="{{asset('theme/js/plugins/bootstrap.min.js')}}"></script>
        <script src="{{asset('theme/js/pcoded.min.js')}}"></script>
        {{-- <script src="{{asset('theme/js/menu-setting.min.js')}}"></script> --}}
        <script src="{{asset('theme/js/plugins/ekko-lightbox.min.js')}}"></script>
        <script src="{{asset('theme/js/plugins/lightbox.min.js')}}"></script>
        <script src="{{asset('theme/js/pages/ac-lightbox.js')}}"></script>
        <script src="{{asset('theme/js/plugins/bootstrap-notify.min.js')}}"></script>
        <script src="{{asset('theme/js/pages/ac-notification.js')}}"></script>
        <script src="{{asset('js/vue.min.js')}}"></script>
        <script src="{{asset('js/axios.min.js')}}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script> --}}

        @yield('js')

        <script>
            var e=$(window)[0].innerWidth;
            $("#mobile-collapse").on("click",function(){
                if(e>991){
                    $(".page-header-title").toggleClass("page-header-collapsed")
                }
            });
            $(window).resize(function() {
                if ($(window).width() < 1201) {
                $('.page-header-title').addClass('page-header-collapsed');
                }
                //else {$('.page-header-title').removeClass('page-header-collapsed');}
            });
            $(function() {
                $(".switcher-hover").mouseenter(function() {
                    $("#switcher-list").toggleClass("fade show active");
                    $("#switcher-top").toggleClass("fade show active");
                });
                $(".switcher-hover").mouseleave(function() {
                    $("#switcher-list").toggleClass("fade show active");
                    $("#switcher-top").toggleClass("fade show active");
                });
            });
        </script>
    </body>
</html>
