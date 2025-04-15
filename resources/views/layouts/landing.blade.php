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
    <header class="kenya-main-header">
        <div class="kenya-header-container">
          <div class="kenya-logo-wrapper">
            <h1 class="kenya-logo-title">
              <a href="{{url('/')}}" class="kenya-logo-link">
                @php
                  $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                @endphp
                @if ($logo_sistema->archivo)
                  <img src="{{asset('storage/'.$logo_sistema->archivo_ruta.'/'.$logo_sistema->archivo)}}" alt="KENYA Logo" class="kenya-logo-img">
                @else
                  <img src="{{asset('theme/images/kenya.png')}}" alt="KENYA" class="kenya-logo-img">
                @endif
              </a>
            </h1>
          </div>

          @yield('menu')
        </div>
      </header><!-- End Header -->

    @yield('content')

    <!-- ======= Footer Kenya ======= -->
<!-- Footer KENYA - Versión mejorada -->
<footer class="kenya-final-footer">
    <div class="kenya-footer-fullwidth">
      <div class="kenya-footer-columns">
        <!-- Columna 1: Información -->
        <div class="kenya-footer-col">
          <h3 class="kenya-footer-heading">Información</h3>
          <ul class="kenya-footer-list">
            <li><a href="#">Quiénes somos</a></li>
            <li><a href="#">Misión y Visión</a></li>
            <li><a href="#">Historia</a></li>
          </ul>
        </div>

        <!-- Columna 2: Atención al cliente -->
        <div class="kenya-footer-col">
          <h3 class="kenya-footer-heading">Atención al cliente</h3>
          <ul class="kenya-footer-list">
            <li><a href="#">Soporte técnico</a></li>
            <li><a href="#">Consulta el estado de tu Producto</a></li>
            <li><a href="#">Preguntas frecuentes</a></li>
            <li><a href="#">Términos y condiciones de garantía</a></li>
          </ul>
        </div>

        <!-- Columna 3: Videos tutoriales -->
        <div class="kenya-footer-col">
          <h3 class="kenya-footer-heading">CONTROLADORES</h3>
          <ul class="kenya-footer-list">
            <li><a href="#">Descargar controladores</a></li>
            <li><a href="#">Estado de la garantía</a></li>
            <li><a href="#">Problemas con la activación</a></li>
          </ul>
        </div>

        <!-- Columna 4: Contáctanos -->
        <div class="kenya-footer-col">
          <h3 class="kenya-footer-heading">Contáctanos</h3>
          <ul class="kenya-footer-list kenya-contact-list">
            <li>
              <i class="kenya-icon fas fa-map-marker-alt"></i>
              <span>Jr. Huallayco 1135 - Huánuco</span>
            </li>
            <li>
              <i class="kenya-icon fas fa-envelope"></i>
              <span>acuerdos.marco@kenya.com.pe</span>
            </li>
            <li>
              <i class="kenya-icon fas fa-envelope"></i>
              <span>soporte@kenya.com.pe</span>
            </li>
            <li>
              <i class="kenya-icon fab fa-whatsapp"></i>
              <span>958 021778</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Footer inferior -->
      <div class="kenya-footer-bottom">
        <div class="kenya-footer-bottom-content">
          <div class="kenya-copyright">
            © Copyright Kenya - Todos los derechos reservados
          </div>
          <div class="kenya-right-section">
            <a href="#" class="kenya-complaint-book">
                <i class="fas fa-book-open"></i> Libro de reclamaciones
              </a>

            <div class="kenya-social-section">
              <span class="kenya-social-text">Síguenos en:</span>
              <div class="kenya-social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
              </div>
            </div>
          </div>
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
