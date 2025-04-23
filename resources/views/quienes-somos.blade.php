@extends('layouts.landing') {{-- Asegúrate de tener tu header/footer aquí --}}

@section('title', 'Quiénes Somos')
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i
                        class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li class="kenya-active"><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="#" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
<style>
    .title-section {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .subtitle {
        font-size: 1.1rem;
        color: #6c757d;
    }

    /* Contenedores modificados */
    .section-box {
        padding: 2rem;
        background: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: 0.3s;
        position: relative; /* Para posicionamiento interno */
    }

    .section-box:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .section-icon {
        width: 64px;
        height: 64px;
        background-color: #f1f1f1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 30px;
        color: #333;
        position: absolute; /* Posicionamiento absoluto */
        top: -32px; /* Mitad del icono fuera del contenedor */
        left: 50%;
        transform: translateX(-50%);
    }

    /* Ajuste para el contenedor principal */
    .container.py-5 {
        padding-top: 2rem !important;
        position: relative;
        margin-top: 55px;
        max-width: 1200px;
    }

    /* Ajuste para las columnas */
    .col-md-6, .col-lg-10 {
        position: relative;
    }

    @media (max-width: 767px) {
        .title-section {
            font-size: 2rem;
        }

        /* Ajuste responsive para los contenedores */

        .section-icon {
            width: 56px;
            height: 56px;
            top: -28px;
            font-size: 26px;
        }
    }
    .about-image-container {
    height: 300px; /* Altura fija */
    width: 100%; /* Ancho completo del contenedor padre */
    display: flex;
    align-items: center;
    justify-content: flex-end;
    background-color: #f1f1f1; /* Fondo gris por defecto */
    border-radius: 0.5rem; /* Coincide con el estilo de tus otros elementos */
    overflow: hidden; /* Para que la imagen no sobresalga del borde redondeado */
}

.about-image-container img {
    height: 100%; /* Ocupa toda la altura del contenedor */
    width: 100%; /* Ocupa todo el ancho del contenedor */
    object-fit: cover; /* Ajusta la imagen manteniendo proporciones */
    object-position: center; /* Centra la imagen */
    border: 5px solid #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

@media (max-width: 991px) {
    .about-image-container {
        height: 250px; /* Altura menor en móviles */
        justify-content: center;
        margin-top: 30px;
    }
}

</style>

<section class="container py-5">
    <!-- Sección Quiénes Somos con imagen al lado derecho -->
    <div class="row mb-5" style="justify-content: space-between;">
        <div class="col-lg-7" style="max-width: 600px;">
            <h1 class="title-section">Quiénes Somos</h1>
            <p class="subtitle">
                Somos KENYA, líderes en soluciones tecnológicas de alto rendimiento, diseño y garantía.
            </p>
        </div>
        <div class="col-lg-5" style="max-width: 600px; flex: none;">
            <div class="about-image-container">
                @if(file_exists(public_path('/images/equipo-kenya.jpg')))
                    <img src="/images/equipo-kenya.jpg" alt="Equipo KENYA" class="img-fluid rounded">
                @endif
            </div>
        </div>
    </div>

    <!-- Secciones de Misión y Visión (sin cambios) -->
    <div class="row text-center mb-5">
        <div class="col-md-6 mb-4">
            <div class="section-box h-100">
                <div class="section-icon mx-auto">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 class="fw-bold mb-3">Misión</h3>
                <p class="subtitle mb-0">
                    Brindar equipos de cómputo de alto desempeño, calidad y eficiencia para empresas, instituciones y usuarios exigentes.
                </p>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="section-box h-100">
                <div class="section-icon mx-auto">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="fw-bold mb-3">Visión</h3>
                <p class="subtitle mb-0">
                    Ser la marca peruana de tecnología más confiable y reconocida en el mercado nacional e internacional.
                </p>
            </div>
        </div>
    </div>

    <!-- Sección Nuestra Historia (sin cambios) -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="section-box">
                <div class="section-icon mx-auto">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="fw-bold mb-3 text-center">Nuestra Historia</h3>
                <p class="subtitle text-center">
                    Desde nuestros inicios en Huánuco, KENYA ha evolucionado para ofrecer soluciones tecnológicas completas. Con una trayectoria de innovación y compromiso, acompañamos a miles de usuarios en su desarrollo digital, promoviendo el crecimiento nacional con orgullo y calidad.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
