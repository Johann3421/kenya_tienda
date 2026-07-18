@extends('layouts.landing')

@section('title', 'Contáctenos')

@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li class="kenya-active"><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <style>
.contact-banner {
            /* Puedes cambiar esta URL por la imagen real que desees de fondo */
            background-image: linear-gradient(rgba(255 255 255 / 50%), rgba(255 255 255 / 50%)), url('banercontacto.png?v=2');
            background-size: cover;
            background-position: right;
            color: #000000;
            text-align: left; /* Texto alineado a la izquierda */
            padding: 90px 20px;  /* Se ajustó para que la alineación dependa del contenedor */
            margin-bottom: 40px;
        }

        .contact-banner h1 {
            font-size: 3rem;
            font-weight: 400;
            margin-bottom: -3px;
        }

        .contact-banner p {
            font-size: 1.2rem;
            font-weight: 300;
        }

        /* ==========================================
           NUEVA SECCIÓN: FORMULARIO E INFORMACIÓN
           ========================================== */
        .contact-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            padding-bottom: 60px;
            background-color: transparent;
            padding: 0px;
            border-radius: 10px;
            box-shadow: none;
            margin-bottom: 60px;
        }

        .contact-layout h2 {
            color: #f26522; /* Azul similar a la imagen adjunta */
            font-size: 2.0rem;
            margin-bottom: 30px;
        }

        /* Estilos del Formulario (Izquierda) */
        .contact-form .form-group {
            margin-bottom: 20px;
        }

        .contact-form label {
            display: block;
            font-weight: 600;
            color: #333333;
            margin-bottom: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .contact-form label span {
            color: #f26522;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #eaeaea;
            background-color: #ffffff;
            border-radius: 6px;
            font-size: 0.95rem;
            color: #333;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #f26522;
            box-shadow: 0 0 0 3px rgba(242, 101, 34, 0.1);
        }

        .contact-form textarea {
            resize: vertical;
            min-height: 140px;
        }

        .contact-form button {
            background: linear-gradient(to right, #ff3c00, #ff9c00);
            color: #ffffff;
            border: none;
            padding: 14px 40px;
            font-size: 1.25rem;
            font-weight: bold;
            border-radius: 100px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .contact-form button:hover {
            background-color: #004c99;
        }

        /* Estilos de Información (Derecha) */
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            font-size: 1.1rem;
            color: #333333;
        }

        .info-item i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #000;
            width: 25px;
            text-align: center;
        }

        .contact-social {
            display: flex;
            gap: 12px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .contact-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background-color: #494949;
            color: #ffffff;
            border-radius: 100px;
            text-decoration: none;
            font-size: 1.3rem;
            transition: background 0.3s;
        }

        .contact-social a:hover {
            background-color: #f26522;
        }

        .contact-map {
            width: 100%;
            height: 280px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #eaeaea;
        }

        /* ==========================================
           FOOTER ORIGINAL
           ========================================== */
        .site-footer {
            background-color: #222;
            color: #ccc;
            font-size: 0.9rem;
            border-top: 0px solid #f26522;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            padding: 4rem 0 2rem;
        }
        .footer-col h4 {
            color: #fff;
            font-size: 1.05rem;
            margin-bottom: 1.2rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        .footer-col h4::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            background-color: #f26522;
        }
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 0.8rem; }
        .footer-col ul li a { color: #aaa; text-decoration: none; transition: color 0.3s ease; }
        .footer-col ul li a:hover { color: #f26522; }

        .contact-info li { display: flex; align-items: flex-start; gap: 10px; color: #fff; }
        .contact-info i { color: #f26522; margin-top: 4px; }

        .footer-bottom-wrapper {
            border-top: 1px solid #333;
        }
        .footer-bottom {
            padding: 1.5rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-extras { display: flex; align-items: center; gap: 20px; }
        .libro-reclamaciones { color: #aaa; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: color 0.3s; }
        .libro-reclamaciones:hover { color: #fff; }
        .social-icons { display: flex; gap: 10px; align-items: center; }
        .social-icons span { color: #aaa; margin-right: 5px; }
        .social-icons a {
            display: flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; background-color: #444; color: #fff;
            border-radius: 50%; text-decoration: none; transition: background 0.3s;
        }
        .social-icons a:hover { background-color: #f26522; }

        /* Responsividad general */
        @media (max-width: 1300px) {
            .header-nav ul { gap: 20px; }
            .header-nav ul li a { font-size: 0.8rem; }
            .header-search { margin: 0 20px; }
        }

        @media (max-width: 992px) {
            .header-content { flex-wrap: wrap; height: auto; padding-top: 15px; padding-bottom: 15px; gap: 15px;}
            .header-left { flex: none; width: 100%; justify-content: center; }
            .header-search { flex: none; width: 100%; justify-content: center; order: 2; max-width: 100%; margin: 0; }
            .header-search-wrapper { max-width: 100%; }
            .header-nav { flex: none; width: 100%; order: 3; justify-content: center; }
            .header-nav ul { flex-wrap: wrap; justify-content: center; gap: 15px;}

            /* Ajuste de formulario en móviles */
            .contact-layout { grid-template-columns: 1fr; }
        }

    </style>

    <section class="contact-banner" style="background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('banercontacto.png?v=2') }}');">
        <div class="container">
            <h1>Comunicate con Nosotros</h1>
            <p>Ponemos a tu disposición todos nuestros canales para atenderte donde estés</p>
        </div>
    </section>

    <div class="container">
        <div class="contact-layout">

            <div class="contact-form-container">
                <h2>Escríbenos</h2>
                <form class="contact-form">
                    <div class="form-group">
                        <label>Nombre y Apellidos <span>*</span></label>
                        <input type="text" placeholder="NOMBRE" required="">
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico <span>*</span></label>
                        <input type="email" placeholder="CORREO ELECTRÓNICO" required="">
                    </div>
                    <div class="form-group">
                        <label>Teléfono de contacto <span>*</span></label>
                        <input type="tel" placeholder="TELÉFONO DE CONTACTO" required="">
                    </div>
                    <div class="form-group">
                        <label>Mensaje <span>*</span></label>
                        <textarea placeholder="Mensaje" required=""></textarea>
                    </div>
                    <button type="submit">Enviar</button>
                </form>
            </div>

            <div class="contact-info-container">
                <h2>Información</h2>
                <div class="info-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>soporte@kenya.com.pe</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-phone"></i>
                    <span>+51 958 021 778</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><strong>Principal:</strong> Jr Huallayco N° 135 - Huánuco</span>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span><strong>Oficina:</strong> Av. Pablo Carriquiry N° 455 - San Isidro - Lima - Perú</span>
                </div>

                <div class="contact-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>

                <div class="contact-map-wrapper">
                    <div class="map-tabs">
                        <button class="map-tab active" data-map="oficina">
                            <i class="fa-solid fa-building"></i> Oficina &mdash; Lima
                        </button>
                        <button class="map-tab" data-map="principal">
                            <i class="fa-solid fa-house"></i> Principal &mdash; Huánuco
                        </button>
                    </div>
                    <div class="map-address-badge" id="mapAddressBadge">
                        <i class="fa-solid fa-location-dot"></i>
                        <span id="mapAddressText">Av. Pablo Carriquiry N° 455 - Oficina 03 - Corpac - San Isidro - Lima</span>
                    </div>
                    <div class="contact-map">
                        <iframe id="mapOficina" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.6172522719596!2d-77.01817840277106!3d-12.096092161538214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c8709d895a81%3A0x3ba45384561942b!2sOficina%2003%2C%20Av%20Pablo%20Carriquiry%20455%2C%20San%20Isidro%2015036!5e0!3m2!1ses!2spe!4v1762792365755!5m2!1ses!2spe" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
                        <iframe id="mapPrincipal" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3929.9701847870056!2d-76.2504416240538!3d-9.93643870620815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91a7c2e77ffd63b3%3A0x64a120db4e5c4fc3!2sJir%C3%B3n%20Huallayco%20135%2C%20Hu%C3%A1nuco%2010003!5e0!3m2!1ses!2spe!4v1784158167240!5m2!1ses!2spe" width="100%" height="100%" style="border:0; display:none;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" sandbox="allow-scripts allow-same-origin allow-popups allow-forms"></iframe>
                    </div>
                </div>

                <style>
                    .contact-map-wrapper { width: 100%; }
                    .map-tabs {
                        display: flex;
                        border-radius: 10px 10px 0 0;
                        overflow: hidden;
                        border: 1px solid #eaeaea;
                        border-bottom: none;
                    }
                    .map-tab {
                        flex: 1;
                        padding: 11px 16px;
                        background: #f5f5f5;
                        border: none;
                        cursor: pointer;
                        font-size: 0.88rem;
                        font-weight: 600;
                        color: #666;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 7px;
                        transition: background 0.2s, color 0.2s;
                        border-right: 1px solid #eaeaea;
                    }
                    .map-tab:last-child { border-right: none; }
                    .map-tab.active {
                        background: #fff;
                        color: #f26522;
                        border-bottom: 2px solid #f26522;
                    }
                    .map-tab:hover:not(.active) { background: #ffe8d6; color: #f26522; }
                    .map-address-badge {
                        background: #fff8f5;
                        border: 1px solid #eaeaea;
                        border-top: none;
                        border-bottom: none;
                        padding: 8px 14px;
                        font-size: 0.82rem;
                        color: #555;
                        display: flex;
                        align-items: center;
                        gap: 7px;
                    }
                    .map-address-badge i { color: #f26522; flex-shrink: 0; }
                    .contact-map {
                        width: 100%;
                        height: 280px;
                        border-radius: 0 0 12px 12px;
                        overflow: hidden;
                        border: 1px solid #eaeaea;
                    }
                    .contact-map iframe { width: 100%; height: 100%; border: 0; }
                </style>

                <script>
                    (function(){
                        const addresses = {
                            oficina: 'Av. Pablo Carriquiry N° 455 - Oficina 03 - Corpac - San Isidro - Lima',
                            principal: 'Jr Huallayco N° 135 - Huánuco'
                        };
                        document.querySelectorAll('.map-tab').forEach(function(btn) {
                            btn.addEventListener('click', function() {
                                const target = this.dataset.map;
                                document.querySelectorAll('.map-tab').forEach(t => t.classList.remove('active'));
                                this.classList.add('active');
                                document.getElementById('mapOficina').style.display   = target === 'oficina'   ? 'block' : 'none';
                                document.getElementById('mapPrincipal').style.display = target === 'principal' ? 'block' : 'none';
                                document.getElementById('mapAddressText').textContent = addresses[target];
                            });
                        });
                    })();
                </script>
            </div>

        </div>
    </div>
@endsection
