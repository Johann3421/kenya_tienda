@extends('layouts.landing')
@section('kenya-menu')
<nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
  <ul class="kenya-nav-list">
    <li class="kenya-active"><a href="{{url('/')}}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
    <li><a href="#" class="kenya-nav-link">Quienes Somos</a></li>
    <li><a href="#" class="kenya-nav-link">Catalogo</a></li>
    <li><a href="#ofertas" class="kenya-nav-link">Ofertas</a></li>
    <li><a href="#novedades" class="kenya-nav-link">Novedades</a></li>
    <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
    <li><a href="#contact" class="kenya-nav-link">Contáctenos</a></li>
  </ul>
</nav>
@endsection


@section('css')
    <style>
        .contorno {
            border: 1px solid #cecece;
            border-radius: 2px;
            background-color: #fff
        }
        .descripcion {
            padding: 7px 9px;
        }
        .p-nombre {
            font-family: "Raleway", sans-serif;
            color: #444;
        }
        .p-nombre:hover {
            color: #000;
            text-decoration: underline;
        }
        .p-precio {
            font-size: 20px;
            color: #1965a7;
        }
        .p-precio-old {
            font-size: 12px;
            color: red;
            text-decoration:line-through;
        }
        .portfolio-wrap {
            width: 250px;
            height: 225px;
            display: flex;
            justify-content: space-around;
        }
        .oferta {
            position: absolute;
            right: -8px;
            top: 8px;
            background-color: red;
            color: #fff;
            padding: 0 10px;
            z-index: 1;
            border: 1px solid #bd0000;
            border-radius: 15px;
        }
        .novedad {
            position: absolute;
            right: -8px;
            top: 8px;
            background-color: #099409;
            color: #fff;
            padding: 0 10px;
            z-index: 1;
            border: 1px solid green;
            border-radius: 15px;
        }
        .team {
            background-color: #f2fff0;
        }
        .botones {
            display: flex;
            flex-wrap: nowrap;
            flex-direction: row;
            justify-content: space-between;
        }

        .botones a:first-child {
            background-color: #2869a1;
            color: #ffffff;
            text-align: center;
            padding: .3rem;
            flex: 1 1 100%;
            border: none;
            transition: border-radius 0.6s linear;
        }

        .botones a:first-child:hover {
            background-color: #124e83;
        }

        .botones a:nth-child(2) {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #57cf57;
            color: #ffffff;
            border-top-left-radius: .5rem;
            border-bottom-left-radius: .5rem;
            flex: 1 1 0%;
            width: 0;
            transition: flex .5s;
        }

        .botones a:nth-child(2):hover {
            background-color: #1bd81b;
        }

        .botones:hover>a:nth-child(2) {
            flex: 1 1 20%;
            margin-left: .5rem;
        }

        .botones:hover>a:first-child {
            border-top-right-radius: .5rem;
            border-bottom-right-radius: .5rem;
        }
    </style>
@endsection
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero">
        <div class="hero-container">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-ride="carousel">

                <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

                <div class="carousel-inner" role="listbox" style="text-align: center;">

                    @foreach ($banners as $banner)
    @if ($banner->imagen)
        <div class="carousel-item @if($loop->index == 0) active @endif" style="background-image: url('storage/{{$banner->imagen}}');">
    @else
        <div class="carousel-item @if($loop->index == 0) active @endif" style="background-image: url('landing/img/slide/slide-{{$loop->index+1}}.jpg');">
    @endif
        <div class="carousel-container" style="text-align: center;">
            <div class="carousel-content container">
                <!-- Título PRINCIPAL (Enorme y destacado) -->
                <h2 style="text-align: center; font-size: 4.5rem; font-weight: 900; text-shadow: 3px 3px 6px rgba(0,0,0,0.7); line-height: 1.1; margin-bottom: 20px;" class="animate__animated animate__fadeInDown">
                    <span style="color: {{$banner->titulo_color}};">{{$banner->titulo}}</span>
                </h2>

                <!-- Subtítulo (Blanco y muy legible) -->
                <h3 style="text-align: center; font-size: 2.5rem; font-weight: 700; color: #ffffff; margin-top: 10px; text-shadow: 2px 2px 5px rgba(0,0,0,0.8); letter-spacing: 0.8px;" class="animate__animated animate__fadeInDown">
                    {{$banner->descripcion}}
                </h3>

                <!-- Descripción (Texto grande y claro) -->
                <p style="text-align: center; font-size: 1.8rem; font-weight: 400; color: #f8f8f8; margin-top: 25px; max-width: 900px; margin-left: auto; margin-right: auto; text-shadow: 1px 1px 3px rgba(0,0,0,0.6); line-height: 1.4;" class="animate__animated animate__fadeInDown">
                    {{$banner->contenido}}
                </p>

                @if ($banner->link)
                    <!-- Botón (Naranja vibrante y llamativo) -->
                    <a href="{{$banner->link}}" class="btn-get-started animate__animated animate__fadeInUp scrollto" style="background-color: #ee7c31; border: none; padding: 15px 40px; font-size: 1.3rem; margin-top: 35px; font-weight: 700; border-radius: 6px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); transition: all 0.3s ease; display: inline-block;">
                        {{$banner->link_nombre}}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach

                    {{-- <div class="carousel-item" style="background-image: url('landing/img/slide/slide-2.jpg');">
                        <div class="carousel-container">
                        <div class="carousel-content container">
                            <h2 class="animate__animated animate__fadeInDown" style="text-align: center;">¿Problemas con su computadora?</h2>
                            <p class="animate__animated animate__fadeInUp" style="text-align: center;">Nosotros nos encargamos de los requerimientos o problemas en sus equipos de cómputo.</p>
                            <a href="#contact" class="btn-get-started animate__animated animate__fadeInUp scrollto" style="text-align: center;">Contáctenos</a>
                        </div>
                        </div>
                    </div> --}}

                    {{-- <div class="carousel-item" style="background-image: url('landing/img/slide/slide-3.jpg');">
                        <div class="carousel-container">
                        <div class="carousel-content container">
                            <h2 class="animate__animated animate__fadeInDown">Venta de equipos y accesorios de cómputo</h2>
                            <p class="animate__animated animate__fadeInUp">Somos distribuidores principales del mercado, comercializamos equipos de calidad para garantizar su compra.</p>
                            <a href="#oferta" class="btn-get-started animate__animated animate__fadeInUp scrollto">Ver Ofertas</a>
                        </div>
                        </div>
                    </div> --}}

                </div>

                <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon icofont-rounded-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon icofont-rounded-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>

            </div>
        </div>
    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Us Section ======= -->
        <section id="productos" class="portfolio section-bg">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>CATEGORIAS</h2>
                        </div>
                        <ul id="portfolio-flters">
                                <li data-filter="*" class="filter-active">
                                    <div class="card" style="width: 8rem;">
                                        <img class="card-img-top" src="{{asset('pord.jpg')}}" alt="Card image cap">
                                        <div class="card-body">
                                        <p class="card-text" style="color:black">Todos</p>
                                        </div>
                                    </div>
                                </li>
                            @foreach ($categorias as $cat)
                                <li  data-filter=".filter-{{$cat->id}}">
                                    <div class="card" style="width: 8rem;">
                                        @if ($cat->img_cat)
                                            <img class="card-img-top" src="{{asset('storage/'.$cat->img_cat)}}" alt="Card image cap">
                                        @else
                                            <img class="card-img-top" src="{{asset('producto.jpg')}}" alt="Card image cap">
                                        @endif
                                            <div class="card-body">
                                                <p class="card-text" style="color:black"> {{$cat->nombre}}</p>
                                            </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row" style="justify-content: center" >
                        @foreach ($modelo as $mod)
                            <div class="col-lg-3 col-md-4 portfolio-item filter-{{$mod->categoria_id}}">
                                <div class="contorno">
                                    <div class="portfolio-wrap" style="margin: 0 auto;">
                                        @if ($mod->img_mod)
                                            <img src="{{asset('storage/'.$mod->img_mod)}}" class="img-fluid" alt="">
                                        @else
                                            <img src="{{asset('producto.jpg')}}" class="img-fluid" alt="">
                                        @endif
                                        <div class="portfolio-info">
                                            @if ($mod->categoria_id)
                                                <h6 style="color: #fff;">{{$mod->descripcion}}</h6>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="descripcion">
                                        <div class="text-center" style="height: 25px;">
                                            <p>{{$mod->descripcion}}</p>
                                        </div>
                                        <div>
                                            <div class="botones">
                                                <a href="{{route('detallemod',$mod->id)}}"> <i class='bx bx-shopping-bag'></i> Ver Catálogo</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </div>
            </div>
        </section>

        <!-- ======= About Lists Section ======= -->
        {{-- <section class="about-lists">
            <div class="container">

            </div>
        </section> --}}

        <!-- ======= Counts Section ======= -->
        {{-- <section class="counts section-bg">
            <div class="container">

                <div class="row">

                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up">
                    <div class="count-box">
                    <i class="icofont-simple-smile" style="color: #20b38e;"></i>
                    <span data-toggle="counter-up">232</span>
                    <p>Happy Clients</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="count-box">
                    <i class="icofont-document-folder" style="color: #c042ff;"></i>
                    <span data-toggle="counter-up">521</span>
                    <p>Projects</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="count-box">
                    <i class="icofont-live-support" style="color: #46d1ff;"></i>
                    <span data-toggle="counter-up">1,463</span>
                    <p>Hours Of Support</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 text-center" data-aos="fade-up" data-aos-delay="600">
                    <div class="count-box">
                    <i class="icofont-users-alt-5" style="color: #ffb459;"></i>
                    <span data-toggle="counter-up">15</span>
                    <p>Hard Workers</p>
                    </div>
                </div>

                </div>

            </div>
        </section> --}}

        <!-- ======= Services Section ======= -->
        <section id="ofertas" class="services portfolio">
            <div class="container">
                <div class="section-title">
                    <h2>Ofertas</h2>
                    <p>Productos con super promociones y descuentos.</p>
                </div>
                <div class="row portfolio-container">
                    @foreach ($ofertas as $prod)
                        <div class="col-lg-3 col-md-4 portfolio-item filter-{{$prod->categoria_id}}">
                            <div class="contorno">
                                <!-- @if ($prod->precio_anterior)
                                    <span class="oferta">Oferta</span>
                                @endif -->
                                <div class="portfolio-wrap" style="margin: 0 auto;">
                                    @if ($prod->imagen_1)
                                        <img src="{{asset('storage/'.$prod->imagen_1)}}" class="img-fluid" alt="">
                                    @else
                                        <img src="{{asset('producto.jpg')}}" class="img-fluid" alt="">
                                    @endif
                                    <div class="portfolio-info">
                                        @if ($prod->categoria_id)
                                            <h6 style="color: #fff;">{{$prod->getCategoria->nombre}}</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="descripcion">
                                    <div class="text-center" style="height: 25px;"><b>{{$prod->codigo_interno}}</b></div>
                                    <div class="text-center" style="height: 85px;"><h6><a href="{{route('producto_detalle', $prod->id)}}" class="p-nombre">{{Str::limit($prod->nombre, 100)}}</a></h6></div>
                                    <div class="text-center">
                                        <b class="p-precio">S/. {{number_format($prod->precio_unitario, 2, '.', ' ')}}</b>
                                        @if ($prod->precio_anterior)
                                            <b class="p-precio-old"> S/.{{number_format($prod->precio_anterior, 2, '.', ' ')}}</b>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ======= Our Portfolio Section ======= -->
        {{-- <section id="portfolio" class="portfolio section-bg">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="section-title">
                    <h2>Productos</h2>
                    <p>Tenemos todos los productos que necesites en cuanto a equipos de Computo se refiere, equipos con la más alta tecnología y lo más top del mercado. Todos nuestros equipos salen con garantía y soporte técnico completamente gratis.</p>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <ul id="portfolio-flters">
                        <li class="filter-active">Computadoras</li>
                        <li >Portatiles</li>
                        <li >Impresoras</li>
                        <li >Accesorios</li>
                        </ul>
                    </div>
                </div>

                <div class="row portfolio-container">
                    <div class="col-lg-4 col-md-6 portfolio-item filter-app">
                        <div class="portfolio-wrap">
                            <img src="{{asset('landing/img/portfolio/portfolio-1.jpg')}}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 1</h4>
                                <p>App</p>
                                <div class="portfolio-links">
                                    <a href="{{asset('landing/img/portfolio/portfolio-1.jpg')}}" data-gall="portfolioGallery" class="venobox" title="App 1"><i class="icofont-eye"></i></a>
                                    <a href="portfolio-details.html" title="More Details"><i class="icofont-external-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-web">
                        <div class="portfolio-wrap">
                            <img src="{{asset('landing/img/portfolio/portfolio-2.jpg')}}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Web 3</h4>
                                <p>Web</p>
                                <div class="portfolio-links">
                                    <a href="{{asset('landing/img/portfolio/portfolio-2.jpg')}}" data-gall="portfolioGallery" class="venobox" title="Web 3"><i class="icofont-eye"></i></a>
                                    <a href="portfolio-details.html" title="More Details"><i class="icofont-external-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 portfolio-item filter-app">
                        <div class="portfolio-wrap">
                            <img src="{{asset('landing/img/portfolio/portfolio-3.jpg')}}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 2</h4>
                                <p>App</p>
                                <div class="portfolio-links">
                                    <a href="{{asset('landing/img/portfolio/portfolio-3.jpg')}}" data-gall="portfolioGallery" class="venobox" title="App 2"><i class="icofont-eye"></i></a>
                                    <a href="portfolio-details.html" title="More Details"><i class="icofont-external-link"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section> --}}

        <!-- ======= Our Team Section ======= -->
        <section id="novedades" class="team portfolio">
            <div class="container">
                <div class="section-title">
                <h2>Novedades</h2>
                <p>Nuevos Productos aumentados a nuestra lista, que esperas !!</p>
                </div>

                <div class="row portfolio-container">
                    @foreach ($novedades as $prod)
                        <div class="col-lg-3 col-md-4 portfolio-item filter-{{$prod->categoria_id}}">
                            <div class="contorno">
                                <span class="novedad">Nuevo</span>
                                <div class="portfolio-wrap" style="margin: 0 auto;">
                                    @if ($prod->imagen_1)
                                        <img src="{{asset('storage/'.$prod->imagen_1)}}" class="img-fluid" alt="">
                                    @else
                                        <img src="{{asset('producto.jpg')}}" class="img-fluid" alt="">
                                    @endif
                                    <div class="portfolio-info">
                                        @if ($prod->categoria_id)
                                            <h6 style="color: #fff;">{{$prod->getCategoria->nombre}}</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="descripcion">
                                    <div class="text-center" style="height: 85px;"><h6><a href="{{route('producto_detalle', $prod->id)}}" class="p-nombre">{{Str::limit($prod->nombre, 100)}}</a></h6></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section><!-- End Our Team Section -->

        <!-- ======= Frequently Asked Questions Section ======= -->
        {{-- <section id="faq" class="faq section-bg">
            <div class="container">

                <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                </div>

                <div class="row  d-flex align-items-stretch">

                <div class="col-lg-6 faq-item" data-aos="fade-up">
                    <h4>Non consectetur a erat nam at lectus urna duis?</h4>
                    <p>
                    Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                    </p>
                </div>

                <div class="col-lg-6 faq-item" data-aos="fade-up" data-aos-delay="100">
                    <h4>Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?</h4>
                    <p>
                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                    </p>
                </div>

                <div class="col-lg-6 faq-item" data-aos="fade-up" data-aos-delay="200">
                    <h4>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi?</h4>
                    <p>
                    Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus.
                    </p>
                </div>

                <div class="col-lg-6 faq-item" data-aos="fade-up" data-aos-delay="300">
                    <h4>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</h4>
                    <p>
                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim.
                    </p>
                </div>

                <div class="col-lg-6 faq-item" data-aos="fade-up" data-aos-delay="400">
                    <h4>Tempus quam pellentesque nec nam aliquam sem et tortor consequat?</h4>
                    <p>
                    Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                    </p>
                </div>

                <div class="col-lg-6 faq-item" data-aos="fade-up" data-aos-delay="500">
                    <h4>Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor?</h4>
                    <p>
                    Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque.
                    </p>
                </div>

                </div>

            </div>
        </section> --}}

        <!-- ======= Contact Us Section ======= -->
        <section id="contact" class="contact">
        <div class="container">

            <div class="section-title">
            <h2>Contactenos</h2>
            </div>

            <div class="row">

            <div class="col-lg-6 d-flex align-items-stretch" data-aos="fade-up">
                <div class="info-box">
                <i class="bx bx-map"></i>
                <h3>Dirección</h3>
                @php
                    $direccion = App\Models\Configuracion::where('nombre', 'contacto_direccion')->first();
                @endphp
                @if ($direccion)
                    <p>{{$direccion->descripcion}}</p>
                @else
                    <p>A108 Adam Street, New York, NY 535022</p>
                @endif
                </div>
            </div>

            <div class="col-lg-3 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
                <div class="info-box">
                <i class="bx bx-envelope"></i>
                <h3>Correo Electrónico</h3>
                @php
                    $email = App\Models\Configuracion::where('nombre', 'contacto_email')->first();
                @endphp
                @if ($email)
                    <p>{{$email->descripcion}}</p>
                @else
                    <p>info@example.com<br>contact@example.com</p>
                @endif
                </div>
            </div>

            <div class="col-lg-3 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
                <div class="info-box ">
                <i class="bx bx-phone-call"></i>
                <h3>Telefonos</h3>
                @php
                    $telefono = App\Models\Configuracion::where('nombre', 'contacto_telefono')->first();
                    $whatsapp = App\Models\Configuracion::where('nombre', 'contacto_whatsapp')->first();
                @endphp
                @if ($telefono && $whatsapp)
                    <p>{{$telefono->descripcion}}<br></p>
                @else
                    <p>+1 5589 55488 55<br>+1 6678 254445 41</p>
                @endif
                </div>
            </div>

            <div class="col-lg-12" data-aos="fade-up" data-aos-delay="300">
                {{-- <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                <div class="form-row">
                    <div class="col-lg-6 form-group">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                    <div class="validate"></div>
                    </div>
                    <div class="col-lg-6 form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                    <div class="validate"></div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                    <div class="validate"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                    <div class="validate"></div>
                </div>
                <div class="mb-3">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>
                </div>
                <div class="text-center"><button type="submit">Send Message</button></div>
                </form> --}}
            </div>

            </div>

        </div>
        </section><!-- End Contact Us Section -->

    </main><!-- End #main -->
@endsection
@section('js')
    <script>
        new Vue({
            el: '#portfolio',
            data: {

            },
            methods: {

            },
        });
    </script>
@endsection
<script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
