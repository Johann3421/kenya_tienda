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
    <link href="{{ asset('landing/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('landing/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('landing/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/venobox/venobox.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('landing/vendor/aos/aos.css') }}" rel="stylesheet">


    <!-- Template Main CSS File -->
    <link href="{{ asset('landing/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .nav-menu>ul>li:before {
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

<style>
    // Agrega esto al CSS global o en el layout principal
html, body {
    height: 100%;
    min-height: 100%;
}

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

#main {
    flex: 1 0 auto;
}

    /* Si tu footer tiene una clase específica, por ejemplo .footer */
.footer {
    flex-shrink: 0;
}

/* Aumentar el ancho máximo de los contenedores para mejor uso del espacio en pantallas anchas */
.container {
    max-width: 1600px !important;
}

/* Hacer header y footer full-width para aprovechar el espacio */
header, footer {
    width: 100%;
    max-width: none !important;
}

/* Centrar el contenido del header */
.kenya-header-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Centrar el contenido del footer */
.kenya-footer-fullwidth {
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 15px;
}
    /* Versión oculta solo para imprimir PDF */
    #print-pdf-container {
        width: 210mm;
        min-height: 297mm;
        background: white;
        padding: 20mm;
        box-sizing: border-box;
        color: black;
        font-family: 'Arial', sans-serif;
        display: none;
    }

    /* Mostramos solo en modo PDF */
    #print-pdf-container.printing {
        display: block;
    }
    </style>

    @yield('css')
</head>

<body>
@hasSection('hide_header_footer')
        {{-- No mostrar header/footer --}}
    @else
    <!-- ======= Header ======= -->
    <header class="kenya-main-header">
        <div class="kenya-header-container">
            <div class="kenya-logo-wrapper">
                <h1 class="kenya-logo-title">
                    <a href="{{ url('/') }}" class="kenya-logo-link">
                        @php
                            $logo_sistema = App\Models\Configuracion::where('nombre', 'logo_sistema')->first();
                        @endphp
                        @if ($logo_sistema->archivo)
                            <img src="{{ asset('storage/' . $logo_sistema->archivo_ruta . '/' . $logo_sistema->archivo) }}"
                                alt="KENYA Logo" class="kenya-logo-img">
                        @else
                            <img src="{{ asset('theme/images/kenya.png') }}" alt="KENYA" class="kenya-logo-img">
                        @endif
                    </a>
                </h1>
            </div>
            <!-- Buscador global en header -->
            <div style="flex:1; display:flex; justify-content:center; align-items:center;">
                <div style="width:100%; max-width:720px;">
                    <input id="productSearch" type="search" placeholder="Buscar productos por nombre o característica..."
                        style="width:100%; padding:10px 14px; border-radius:30px; border:1px solid #ddd; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                    <div id="searchResults" style="position:absolute; z-index:9999; display:none; margin-top:8px; width:calc(100% - 40px); background:#fff; border:1px solid #e6e6e6; border-radius:8px; max-height:360px; overflow:auto; box-shadow:0 8px 24px rgba(0,0,0,0.08);"></div>
                </div>
            </div>

            @yield('menu')

            <!-- Botón hamburguesa para mobile -->
            <button class="kenya-mobile-menu-toggle" id="kenyaMobileMenuToggle" title="Menú">
                <i class="bx bx-menu"></i>
            </button>
        </div>

        <!-- Menú móvil dropdown -->
        <nav class="kenya-mobile-menu" id="kenyaMobileMenu">
            <ul>
                <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                <li><a href="{{ route('quienes.somos') }}">Quienes Somos</a></li>
                <li><a href="{{ route('catalogo') }}">Catalogo</a></li>
                <li><a href="{{ route('novedades') }}">Novedades</a></li>
                <li><a href="{{ route('consultar.garantia') }}">Soporte</a></li>
                {{-- Sorteo temporalmente oculto en producción --}}
                {{-- <li><a href="{{ route('serial.draw') }}">🎁 Sorteo</a></li> --}}
                <li><a href="{{ route('contactenos') }}">Contáctenos</a></li>
            </ul>
        </nav>
    </header><!-- End Header -->
@endif
    @yield('content')

    @hasSection('hide_header_footer')
        {{-- No mostrar footer --}}
    @else
    <!-- ======= Footer Kenya ======= -->
    <!-- Footer KENYA - Versión mejorada -->
    <footer class="kenya-final-footer">
        <div class="kenya-footer-fullwidth">
            <div class="kenya-footer-columns">
                <!-- Columna 1: Información -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Información</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('quienes.somos') }}">Quiénes somos</a></li>
                        <li><a href="{{ route('quienes.somos') }}">Misión y Visión</a></li>
                        <li><a href="{{ route('quienes.somos') }}">Historia</a></li>
                    </ul>
                </div>

                <!-- Columna 2: Atención al cliente -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Atención al cliente</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('consultar.garantia') }}">Consulta el estado de tu Producto</a></li>
                        <li><a href="{{ route('contactenos') }}">Preguntas frecuentes</a></li>
                        <li><a href="{{ route('consultar.garantia') }}#terms">Términos y condiciones de garantía</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Videos tutoriales -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">CONTROLADORES</h3>
                    <ul class="kenya-footer-list">
                        <li><a href="{{ route('consultar.garantia') }}">Descargar controladores</a></li>
                        <li><a href="{{ route('consultar.garantia') }}">Estado de la garantía</a></li>
                        <li><a href="{{ route('consultar.garantia') }}">Problemas con la activación</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Contáctanos -->
                <div class="kenya-footer-col">
                    <h3 class="kenya-footer-heading">Contáctanos</h3>
                    <ul class="kenya-footer-list kenya-contact-list">
                        <li>
                            <i class="kenya-icon fas fa-map-marker-alt"></i>
                            <span>Av. Pablo Carriquiry N°455 Oficina 03 - Corpac - San Isidro - Lima - Perú</span>
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
                            <span>958 021 778</span>
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
                        <a href="{{ route('reclamaciones') }}" class="kenya-complaint-book">
                            <i class="fas fa-book-open"></i> Libro de reclamaciones
                        </a>

                        <div class="kenya-social-section">
                            <span class="kenya-social-text">Síguenos en:</span>
                            <div class="kenya-social-icons">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="{{ url('/login') }}" title="Iniciar sesión" style="margin-left:8px;">
            <i class="fas fa-user"></i>
        </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- End Footer -->


    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
@endif
    <!-- Vendor JS Files -->
    <script src="{{ asset('landing/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('landing/vendor/jquery-sticky/jquery.sticky.js') }}"></script>
    <script src="{{ asset('landing/vendor/venobox/venobox.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('landing/vendor/aos/aos.js') }}"></script>

    <!-- Toastr para notificaciones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('landing/js/main.js') }}"></script>
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.1/iconify-icon.min.js"></script>

    {{-- CSS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js"
        integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
    <script>
        (function(){
            const input = document.getElementById('productSearch');
            const resultsBox = document.getElementById('searchResults');
            let timer = null;

            function renderResults(items) {
                if (!resultsBox) return;
                if (!items || items.length === 0) {
                    resultsBox.style.display = 'none';
                    resultsBox.innerHTML = '';
                    return;
                }
                resultsBox.style.display = 'block';
                resultsBox.innerHTML = items.map(i => `
                    <a href="${i.url}" class="search-item" style="display:flex; gap:12px; padding:10px; border-bottom:1px solid #f2f2f2; align-items:center; text-decoration:none; color:#333;">
                        <img src="${i.img}" style="width:56px; height:56px; object-fit:cover; border-radius:6px;" alt="${i.nombre}">
                        <div style="flex:1">
                            <div style="font-weight:600;">${i.nombre}</div>
                            <div style="font-size:12px; color:#666;">${(i.descripcion || '').substring(0,120)}</div>
                        </div>
                    </a>`).join('');
            }

            async function doSearch(q){
                if (!q || q.trim().length < 2) {
                    renderResults([]);
                    return;
                }
                try {
                    const res = await fetch(`{{ route('search.products') }}?q=` + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    renderResults(json.data || []);
                } catch (err) {
                    console.error('Search error', err);
                    renderResults([]);
                }
            }

            if (input) {
                input.addEventListener('input', function(e){
                    clearTimeout(timer);
                    const q = e.target.value;
                    timer = setTimeout(() => doSearch(q), 300);
                });
            }

            // Ensure dropdown width and positioning follow the input
            function syncSearchDropdown() {
                if (!input || !resultsBox) return;
                // make parent of input a positioned container (in case inline styles change)
                const parent = input.parentElement;
                if (parent) parent.style.position = parent.style.position || 'relative';
                // ensure results box fills the parent width
                resultsBox.style.width = '100%';
                resultsBox.style.left = '0';
                resultsBox.style.transform = 'none';
            }

            // initial sync and on resize
            syncSearchDropdown();
            window.addEventListener('resize', syncSearchDropdown);

            // close on outside click
            document.addEventListener('click', function(ev){
                if (!resultsBox) return;
                if (!resultsBox.contains(ev.target) && ev.target !== input) {
                    resultsBox.style.display = 'none';
                }
            });
        })();
    </script>

    <!-- Script para Menú Móvil -->
    <script>
        (function(){
            const toggleBtn = document.getElementById('kenyaMobileMenuToggle');
            const mobileMenu = document.getElementById('kenyaMobileMenu');
            const menuLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];

            if (toggleBtn && mobileMenu) {
                // Toggle menu on button click
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('active');
                });

                // Close menu when clicking on a link
                menuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.remove('active');
                    });
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.kenya-main-header')) {
                        mobileMenu.classList.remove('active');
                    }
                });

                // Close menu on scroll
                window.addEventListener('scroll', function() {
                    if (mobileMenu.classList.contains('active')) {
                        mobileMenu.classList.remove('active');
                    }
                });
            }
        })();
    </script>

    @yield('js')

</body>

</html>

