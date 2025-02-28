<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>KENYA</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('landing/img/favicon.png')}}" rel="icon">
    <link href="{{asset('landing/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,600,600i,700,700i,900" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('landing/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/venobox/venobox.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('landing/vendor/aos/aos.css')}}" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{asset('landing/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .nav-menu > ul > li:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: 2px;
            left: 0;
            background-color: #428bca;
            visibility: hidden;
            width: 0px;
            transition: all 0.3s ease-in-out 0s;
        }
        .nav-menu .active:before {
            visibility: visible;
            width: 100%;
        }
    </style>

    @yield('css')
</head>

<body>

    <!-- ======= Top Bar ======= -->
    <!-- <section id="topbar" class="d-none d-lg-block">
        <div class="container clearfix">
        <div class="contact-info float-left">
            @php
                $email = App\Models\Configuracion::where('nombre', 'contacto_email')->first();
                $telefono = App\Models\Configuracion::where('nombre', 'contacto_telefono')->first();
                $whatsapp = App\Models\Configuracion::where('nombre', 'contacto_whatsapp')->first();
            @endphp
            @if ($email)
                <i class="icofont-envelope"></i><a href="mailto:contact@example.com">{{$email->descripcion}}</a>
            @else
                <i class="icofont-envelope"></i><a href="mailto:contact@example.com">contact@example.com</a>
            @endif
            @if ($telefono)
                <i class="icofont-phone"></i> +51 {{$telefono->descripcion}}
            @else
                <i class="icofont-phone"></i> +51 953 708 979
            @endif
        </div>
        <div class="social-links float-right">
            <a href="#" class="twitter"><i class="icofont-twitter"></i></a>
            <a href="https://www.facebook.com/GRUPOVASCOEIRL" class="facebook"><i class="icofont-facebook"></i></a>
            <a href="#" class="instagram"><i class="icofont-instagram"></i></a>
            <a href="#" class="skype"><i class="icofont-skype"></i></a>
            @auth
                <a href="{{ url('/home') }}" title="Inicio de Sistema"><i class="bx bxs-dashboard"></i></i></a>
            @else
                <a href="{{ route('login') }}" title="Iniciar Sesión"><i class="icofont-login"></i></i></a>
            @endauth
        </div>
        </div>
    </section> -->

    <!-- ======= Header ======= -->
    <header id="header">
        <div class="container">

            <div class="logo float-left">
                <h1 class="text-light"><a href="{{url('/')}}">
                    @php
                        $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                    @endphp
                    @if ($logo_sistema->archivo)
                        <img src="{{asset('storage/'.$logo_sistema->archivo_ruta.'/'.$logo_sistema->archivo)}}" alt="" class="logo" style="width: 140px;">
                    @else
                        <img src="{{asset('theme/images/kenya.png')}}" alt="KENYA" class="img-fluid">
                    @endif
                </a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->
                <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
            </div>

            @yield('menu')

        </div>
    </header><!-- End Header -->

    @yield('content')

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
        <div class="container">
            <div class="row">

            <div class="col-lg-3 col-md-6 footer-info">
                <h3>Contáctenos</h3>
                <p>
                    ¿Tiene problemas técnicos con sus equipos de cómputo?.<br><br>
                    Jr. Huallayco #1135 Huanuco <br>
                    Huánuco, Perú<br><br>
                    <strong>Telefono:</strong> +51 958 021 778<br>
                    <strong>Email:</strong> acuerdos.marco@kenya.com.pe<br>
                </p>
            </div>

            <div class="col-lg-2 col-md-6 footer-links">
                <h4>Navegar</h4>
                <ul>
                    <li><i class="bx bx-chevron-right"></i> <a href="{{url('/')}}">Inicio</a></li>
                    <li><i class="bx bx-chevron-right"></i> <a href="#productos">Productos</a></li>
                    <li><i class="bx bx-chevron-right"></i> <a href="#ofertas">Ofertas</a></li>
                    <li><i class="bx bx-chevron-right"></i> <a href="#novedades">Novedades</a></li>
                    <li><i class="bx bx-chevron-right"></i> <a href="#catalogo">Catálogo</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
                <h4>Algunos de nuestros servicios</h4>
                <ul>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Limpieza y mantenimiento de Pc's</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Instalación de Programs</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Formateo de equipos</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Reportenciado de equipos</a></li>
                <li><i class="bx bx-chevron-right"></i> <a href="#">Cambio de Disco Duro</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6 footer-newsletter">
                <h4>KENYA</h4>
                <p>Somos especialistas en soporte técnico integral. En KENYA nos preocupamos en brindarle un servicio eficiente con la misma calidad y seguridad que nos gustaría tener.</p>
                <div class="input-group mb-3" id="whatsapp">
                    <input type="text" v-model="mensaje" class="form-control" placeholder="Consultenos x Whatsapp" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <a target="_blank" :href="'https://wa.me/+51958021778?text=Buenos días GRUPO KENYA E.I.R.L,quiero consultarte por lo siguiente:*'+mensaje+'*'" class="btn btn-success" type="button"><i class="bx bxl-whatsapp"></i> Encribanos</a>
                    </div>
                </div>
            </div>

            </div>
        </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>KENYA</span></strong>. Todos los derechos reservados
                <div class="contact-info float-left">
            @php
                $email = App\Models\Configuracion::where('nombre', 'contacto_email')->first();
                $telefono = App\Models\Configuracion::where('nombre', 'contacto_telefono')->first();
                $whatsapp = App\Models\Configuracion::where('nombre', 'contacto_whatsapp')->first();
            @endphp
            @if ($email)
                <i class="icofont-envelope"></i> <a href="mailto:contact@example.com">{{$email->descripcion}}</a>
                <br>
            @else
                <i class="icofont-envelope"></i> <a href="mailto:contact@example.com">contact@example.com</a>
                <br>
            @endif
            @if ($telefono)
                <i class="icofont-phone"></i> +51 {{$telefono->descripcion}}
            @else
                <i class="icofont-phone"></i> +51 953 708 979
            @endif
        </div>
        <div class="social-links float-right">
            <a href="#" class="twitter"><i class="icofont-twitter"></i></a>
            <a href="https://www.facebook.com/KENYA" class="facebook"><i class="icofont-facebook"></i></a>
            <a href="#" class="instagram"><i class="icofont-instagram"></i></a>
            <a href="#" class="skype"><i class="icofont-skype"></i></a>
            @auth
                <a href="{{ url('/home') }}" title="Inicio de Sistema"><i class="bx bxs-dashboard"></i></i></a>
            @else
                <a href="{{ route('login') }}" title="Iniciar Sesión"><i class="icofont-login"></i></i></a>
            @endauth
        </div>
            </div>

        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{asset('landing/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('landing/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('landing/vendor/php-email-form/validate.js')}}"></script>
    <script src="{{asset('landing/vendor/jquery-sticky/jquery.sticky.js')}}"></script>
    <script src="{{asset('landing/vendor/venobox/venobox.min.js')}}"></script>
    <script src="{{asset('landing/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('landing/vendor/counterup/counterup.min.js')}}"></script>
    <script src="{{asset('landing/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
    <script src="{{asset('landing/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('landing/vendor/aos/aos.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('landing/js/main.js')}}"></script>
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>

    {{-- CSS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        new Vue({
            el: '#whatsapp',
            data: {
                mensaje: null,
            },
            method: {
                Whatsapp() {

                }
            }
        });
    </script>

    @yield('js')

</body>
</html>
