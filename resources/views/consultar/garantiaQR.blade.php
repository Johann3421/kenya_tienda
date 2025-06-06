@extends('layouts.landing')
@section('css')
    <style>
        .col1 {
            width: 10%;
        }

        .col2 {
            width: 10%;
        }

        .col3 {
            width: 20%;
        }

        .col4 {
            width: 45%;
        }

        .col5 {
            width: 15%;
        }

        .table-sm td {
            vertical-align: middle !important;
        }

        .E1,
        .E2,
        .E3,
        .E4,
        .E5,
        .E6 {
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }

        .E1 {
            background-color: red;
        }

        .E2 {
            background-color: #00c1c1;
        }

        .E3 {
            background-color: purple;
        }

        .E4 {
            background-color: orange;
        }

        .E5 {
            background-color: green;
        }

        .E6 {
            background-color: #0077ff;
        }

        pre {
            font-family: 'Raleway', sans-serif;
            padding: 5px 10px;
            margin-bottom: 0;
        }

        img {
            max-width: 100%;
            max-height: 100%;
        }

        .cat {
            height: 200px;
            width: 200px;
        }
    </style>
    <style>
        .warranty-section {
            padding: 2rem 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .warranty-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Estilo de las pestañas */
        .tabs-container {
            margin-bottom: 2rem;
        }

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-item {
            margin-bottom: -2px;
        }

        .nav-link {
            color: #6c757d;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s;
            border-radius: 0;
            display: flex;
            align-items: center;
        }

        .nav-link i,
        .nav-link iconify-icon {
            margin-right: 8px;
            font-size: 18px;
        }

        .nav-link.active {
            color: #fff;
            background-color: #E67E22;
            /* Naranja más oscuro */
            border-color: transparent;
            border-bottom: 3px solid #D35400;
        }

        .nav-link.disabled {
            color: #adb5bd;
            pointer-events: none;
        }

        .nav-link:not(.active):not(.disabled):hover {
            color: #E67E22;
            border-color: transparent;
            background-color: rgba(230, 126, 34, 0.1);
        }

        /* Contenido de las pestañas */
        .tab-content {
            background: white;
            border-radius: 0 8px 8px 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        /* Estilos generales (manteniendo los anteriores con ajustes de color) */
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .search-box {
            width: 100%;
            max-width: 500px;
        }

        .modern-input-group {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .modern-input {
            border: none;
            padding: 12px 15px;
            font-size: 16px;
            background-color: white;
        }

        .modern-input:focus {
            box-shadow: none;
            border-color: #E67E22;
        }

        .search-button {
            background-color: #E67E22;
            /* Naranja más oscuro */
            color: white;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background-color: #D35400;
            /* Naranja más oscuro - hover */
            transform: translateY(-1px);
        }

        .error-message {
            font-size: 13px;
            color: #dc3545;
            margin-top: 5px;
            text-align: center;
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modern-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(230, 126, 34, 0.3);
            /* Naranja más oscuro */
            border-radius: 50%;
            border-top-color: #E67E22;
            /* Naranja más oscuro */
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 1rem;
        }

        .loading-text {
            font-size: 1.25rem;
            color: #6c757d;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .warranty-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background-color: #E67E22;
            /* Naranja más oscuro */
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 18px;
        }

        .card-content {
            display: flex;
            padding: 20px;
            flex-wrap: wrap;
        }

        .details-column,
        .tech-column {
            padding: 0 15px;
            flex: 1;
            min-width: 300px;
        }

        .details-column {
            border-right: 1px solid #eee;
        }

        .section-title {
            font-weight: 700;
            font-size: 16px;
            color: #343a40;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-title i,
        .section-title iconify-icon {
            margin-right: 8px;
            font-size: 18px;
            color: #E67E22;
            /* Naranja más oscuro */
        }

        .detail-item {
            font-size: 14px;
            margin-bottom: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
        }

        .detail-item i,
        .detail-item iconify-icon {
            width: 20px;
            text-align: center;
            color: #E67E22;
            /* Naranja más oscuro */
        }

        .product-image-container {
            margin: 20px 0;
            text-align: center;
        }

        .product-image {
            max-height: 180px;
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .warranty-progress {
            margin: 20px 0;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 5px;
            transition: width 0.6s ease;
        }

        .progress.active .progress-bar {
            background-color: #E67E22;
            /* Naranja más oscuro */
        }

        .progress.expired .progress-bar {
            background-color: #dc3545;
        }

        .progress.expiring .progress-bar {
            background-color: #17a2b8;
        }

        .expiration-message {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .expiration-message.active {
            background-color: rgba(230, 126, 34, 0.1);
            /* Naranja más oscuro */
            color: #E67E22;
            /* Naranja más oscuro */
        }

        .expiration-message.expired {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .expiration-message.expiring {
            background-color: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .tech-specs {
            margin-top: 20px;
        }

        .tech-link {
            color: #E67E22;
            /* Naranja más oscuro */
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #E67E22;
            /* Naranja más oscuro */
            border-radius: 5px;
        }

        .tech-link:hover {
            color: #D35400;
            /* Naranja más oscuro - hover */
            background-color: rgba(230, 126, 34, 0.1);
            /* Naranja más oscuro */
        }

        .tech-link iconify-icon {
            margin-right: 8px;
        }

        .manual-item {
            margin-bottom: 10px;
        }

        .manual-item a {
            color: #495057;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .manual-item a:hover {
            color: #E67E22;
            /* Naranja más oscuro */
        }

        .manual-item iconify-icon {
            margin-right: 8px;
            color: #E67E22;
            /* Naranja más oscuro */
        }

        /* Estilos para la pestaña de controladores */
        .drivers-container {
            padding: 20px;
        }

        .drivers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .driver-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            border-left: 4px solid #E67E22;
            /* Naranja más oscuro */
            transition: transform 0.2s;
        }

        .driver-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .driver-name {
            font-weight: 600;
            margin-bottom: 10px;
            color: #343a40;
        }

        .driver-download {
            color: #E67E22;
            /* Naranja más oscuro */
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            transition: color 0.2s;
        }

        .driver-download:hover {
            color: #D35400;
            /* Naranja más oscuro - hover */
        }

        .driver-download iconify-icon {
            margin-right: 5px;
        }

        /* Estilos para la pestaña de videos */
        .video-gallery {
            padding: 20px;
        }

        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .video-item {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .video-title {
            padding: 12px;
            font-weight: 600;
            color: #343a40;
            text-align: center;
        }

        /* Estilos para términos y condiciones */
        .terms-container {
            padding: 20px;
        }

        .terms-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }

        .terms-content p {
            margin-bottom: 10px;
            color: #495057;
            line-height: 1.6;
        }

        .no-results,
        .no-data {
            text-align: center;
            padding: 2rem;
            font-size: 18px;
            color: #6c757d;
        }

        .no-results strong,
        .no-data i {
            color: #343a40;
        }

        .no-data i {
            font-size: 24px;
            display: block;
            margin-bottom: 10px;
            color: #E67E22;
            /* Naranja más oscuro */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-content {
                flex-direction: column;
            }

            .details-column {
                border-right: none;
                border-bottom: 1px solid #eee;
                padding-bottom: 20px;
                margin-bottom: 20px;
            }

            .nav-link {
                padding: 10px 12px;
                font-size: 14px;
            }
        }

        /* Estilos para la barra de progreso por etapas */
        .warranty-progress {
            margin: 20px 0;
        }

        .progress {
            height: 20px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-bar {
            height: 100%;
            transition: width 0.6s ease;
        }

        /* Colores para cada etapa */
        .new-stage {
            background-color: #4CAF50;
            /* Verde */
        }

        .mid-stage {
            background-color: #FFC107;
            /* Amarillo */
        }

        .ending-stage {
            background-color: #F44336;
            /* Rojo */
            animation: pulse 1.5s infinite;
        }

        .expired-stage {
            background-color: #9E9E9E;
            /* Gris */
        }

        /* Animación para la etapa "Por vencer" */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        /* Estilos para las etiquetas de estado */
        .warranty-stage-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .warranty-stage-info span {
            font-size: 12px;
            color: #6c757d;
            display: flex;
            align-items: center;
        }

        .warranty-stage-info span.active {
            font-weight: bold;
            color: #000;
        }

        .warranty-stage-info i {
            margin-right: 5px;
        }

        /* Mensajes de expiración */
        .expiration-message {
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .expiration-message.new {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .expiration-message.mid {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        .expiration-message.ending {
            background-color: #ffebee;
            color: #c62828;
            font-weight: bold;
        }

        .expiration-message.expired {
            background-color: #f5f5f5;
            color: #616161;
        }
    </style>
@endsection
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li class="kenya-active"><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection
@section('content')
    <main id="main">

        <!-- ======= Breadcrumbs Section ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>Buscar soporte</h2>
                    <ol>
                        <li><a href="{{ url('/') }}"><i class="bx bx-home"></i> Inicio</a></li>
                        <li>Consultar</li>
                    </ol>
                </div>

            </div>
        </section><!-- Breadcrumbs Section -->

        <section class="warranty-section" id="garantia">
            <div class="warranty-container">
                <!-- Pestañas -->
                <div class="tabs-container">
                    <ul class="nav nav-tabs" id="warrantyTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="warranty-tab" data-toggle="tab" href="#warranty" role="tab">
                                <i class="bx bx-shield"></i> Garantía
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="drivers-tab" data-toggle="tab" href="#drivers" role="tab">
                                <i class="bx bx-chip"></i> Controladores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="gallery-tab" data-toggle="tab" href="#gallery" role="tab">
                                <i class="bx bx-video"></i> Galería de Video
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms" role="tab">
                                <i class="bx bx-file"></i> Términos y Condiciones
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="warrantyTabsContent">
                        <!-- Pestaña de Garantía -->
                        <div class="tab-pane fade show active" id="warranty" role="tabpanel">
                            <div class="warranty-card">
                                <div class="card-header">
                                    <div class="product-title" v-for="nom in garantia.get_productos">
                                        Producto: @{{ nom.nombre }}
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="details-column">
                                        <div class="section-title">
                                            <iconify-icon icon="zondicons:align-center"></iconify-icon> DETALLES
                                        </div>
                                        <div class="detail-item">
                                            <i class="fa-solid fa-tv"></i> Serie: @{{ garantia.serie }}
                                        </div>
                                        <div class="detail-item">
                                            <iconify-icon icon="bx:calendar"></iconify-icon> Inicia: @{{ garantia.fecha_venta }}
                                        </div>
                                        <div class="detail-item">
                                            <iconify-icon icon="bx:time"></iconify-icon> Garantía: @{{ garantia.garantia }}
                                            Meses
                                        </div>
                                        <div class="product-image-container">
                                            <img v-for="producto in garantia.get_productos"
                                                :src="producto.modelo && producto.modelo.img_mod ?
                                                    '/storage/' + producto.modelo.img_mod :
                                                    (producto.imagen_1 ? '/storage/' + producto.imagen_1 :
                                                        '/producto.jpg')"
                                                class="product-image" :alt="'Imagen de ' + producto.nombre">
                                        </div>

                                        <!-- Nuevo sistema de progreso por etapas -->
                                        <div class="warranty-progress">
                                            <div class="progress">
                                                <div class="progress-bar"
                                                    :style="'width:' + calcularPorcentajeGarantia() + '%'"
                                                    :class="getWarrantyStageClass()">
                                                    <span class="progress-text" v-if="showDaysCount()">
                                                        @{{ diasRestantes }} días restantes
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="warranty-stage-info">
                                                <span :class="{ 'active': warrantyStage === 'expired' }">
                                                    <i class="bx bx-x-circle"></i> Vencida
                                                </span>
                                                <span :class="{ 'active': warrantyStage === 'ending' }">
                                                    <i class="bx bx-error-circle"></i> Por vencer
                                                </span>
                                                <span :class="{ 'active': warrantyStage === 'mid' }">
                                                    <i class="bx bx-time"></i> Intermedia
                                                </span>
                                                <span :class="{ 'active': warrantyStage === 'new' }">
                                                    <i class="bx bx-check-circle"></i> Nueva
                                                </span>
                                            </div>
                                        </div>

                                        <div class="expiration-message" :class="warrantyStage">
                                            <iconify-icon icon="bx:calendar"></iconify-icon>
                                            <span v-if="warrantyStage === 'new'">
                                                Garantía nueva - @{{ diasRestantes }} días restantes (Vence:
                                                @{{ garantia.fecha_Vencimiento }})
                                            </span>
                                            <span v-else-if="warrantyStage === 'mid'">
                                                Garantía en etapa intermedia - @{{ diasRestantes }} días restantes (Vence:
                                                @{{ garantia.fecha_Vencimiento }})
                                            </span>
                                            <span v-else-if="warrantyStage === 'ending'">
                                                ¡Garantía por vencer! - Solo @{{ diasRestantes }} días restantes (Vence:
                                                @{{ garantia.fecha_Vencimiento }})
                                            </span>
                                            <span v-else-if="warrantyStage === 'expired'">
                                                Garantía vencida el @{{ garantia.fecha_Vencimiento }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="tech-column" v-for="det in garantia.get_productos">
                                        <div class="section-title">
                                            <i class="bx bx-chip"></i> ESPECIFICACIONES
                                        </div>
                                        <div class="tech-specs">
                                            <p v-if="det.procesador"><i class="bx bx-right-arrow"></i>
                                                @{{ det.procesador }}</p>
                                            <p v-if="det.ram"><i class="bx bx-right-arrow"></i>
                                                @{{ det.ram }}</p>
                                            <p v-if="det.almacenamiento"><i class="bx bx-right-arrow"></i>
                                                @{{ det.almacenamiento }}</p>
                                            <p v-if="det.sistema_operativo"><i class="bx bx-right-arrow"></i>
                                                @{{ det.sistema_operativo }}</p>
                                            <p v-if="det.suite_ofimatica"><i class="bx bx-right-arrow"></i>
                                                @{{ det.suite_ofimatica }}</p>
                                        </div>

                                        <div class="section-title" style="margin-top: 20px;">
                                            <i class="bx bx-file-blank"></i> DOCUMENTOS
                                        </div>
                                        <div class="tech-specs">
                                            <a :href="'../storage/' + det.ficha_tecnica" target="_blank"
                                                class="tech-link">
                                                <iconify-icon icon="bx:download"></iconify-icon> FICHA TÉCNICA
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Controladores -->
                        <div class="tab-pane fade" id="drivers" role="tabpanel">
                            <div class="drivers-container">
                                <div class="section-title">
                                    <i class="bx bx-chip"></i> CONTROLADORES DISPONIBLES
                                </div>
                                <div v-if="garantia.get_driversprod && garantia.get_driversprod.get_drivers && garantia.get_driversprod.get_drivers.length"
                                    class="drivers-grid">
                                    <div v-for="drivers in garantia.get_driversprod.get_drivers" class="driver-card">
                                        <div class="driver-name">@{{ drivers.nombre }}</div>
                                        <a :href="'../storage/' + drivers.link" target="_blank" class="driver-download">
                                            <iconify-icon icon="bx:download"></iconify-icon> Descargar
                                        </a>
                                    </div>
                                </div>
                                <div v-else class="no-data">
                                    <i class="bx bx-info-circle"></i> No hay controladores disponibles.
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Galería de Video -->
                        <div class="tab-pane fade" id="gallery" role="tabpanel">
                            <div class="video-gallery">
                                <div class="section-title">
                                    <i class="bx bx-video"></i> VIDEOS RELACIONADOS
                                </div>
                                <div class="videos-grid">
                                    <div class="video-item">
                                        <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/ejemplo1" frameborder="0"
                                            allowfullscreen></iframe>
                                        <div class="video-title">Instalación del Producto</div>
                                    </div>
                                    <div class="video-item">
                                        <iframe width="100%" height="200"
                                            src="https://www.youtube.com/embed/ejemplo2" frameborder="0"
                                            allowfullscreen></iframe>
                                        <div class="video-title">Configuración Básica</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestaña de Términos y Condiciones -->
                        <div class="tab-pane fade" id="terms" role="tabpanel">
                            <div class="terms-container">
                                <div class="section-title">
                                    <i class="bx bx-file"></i> TÉRMINOS Y CONDICIONES DE GARANTÍA
                                </div>
                                <div class="terms-content">
                                    <p>1. La garantía cubre defectos de fabricación bajo condiciones normales de uso.</p>
                                    <p>2. El período de garantía es de @{{ garantia.garantia || 'X' }} meses a partir de la fecha de
                                        compra.</p>
                                    <p>3. La garantía no cubre daños por mal uso, accidentes o modificaciones no
                                        autorizadas.</p>
                                    <p>4. Para hacer válida la garantía debe presentar este comprobante y el producto con su
                                        número de serie legible.</p>
                                    <p>5. La garantía no incluye daños por fenómenos naturales o condiciones ambientales
                                        extremas.</p>
                                    <p>6. El servicio de garantía puede incluir reparación o reemplazo del producto a
                                        criterio del fabricante.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
@section('js')
    <script>
        var my_whatsapp = {!! json_encode($whatsapp) !!};
        var mi_fecha = {!! json_encode(date('Y-m-d')) !!};
        let serie_id = {!! json_encode(isset($serie) ? $serie : '') !!};
        const garantiaphp = {!! json_encode($garantia) !!};
    </script>

    <script>
        new Vue({
            el: '#garantia',
            data: {
                search: '',
                loading: false,
                garantia: [],
                errors: [],
                state: null,
                whatsapp: my_whatsapp,
                vencido: mi_fecha,
                // Nuevas propiedades para el sistema de etapas
                warrantyStage: 'new',
                diasRestantes: 0,
                mesesTotalesGarantia: 0
            },
            created() {
                if (serie_id !== '') {
                    this.search = serie_id;
                }
                this.garantia = garantiaphp;

                // Calcular el estado inicial si hay datos de garantía
                if (this.garantia && this.garantia.fecha_Vencimiento) {
                    this.calcularPorcentajeGarantia();
                }
            },
            methods: {
                getProductImageUrl(producto) {
        // Verificar si es un caso especial (toner o modelo ID 10)
        const isSpecialCase = producto.modelo &&
            (producto.modelo.id === 10 ||
             (producto.modelo.descripcion &&
              producto.modelo.descripcion.toLowerCase().includes('toner')));

        // Si es caso especial, usar imagen_1 si existe
        if (isSpecialCase) {
            return producto.imagen_1 ? this.generateImageUrl(producto.imagen_1) : '/producto.jpg';
        }

        // Para casos normales, usar img_mod del modelo si existe
        if (producto.modelo && producto.modelo.img_mod) {
            return this.generateImageUrl(producto.modelo.img_mod);
        }

        // Fallback a imagen_1 si no hay modelo
        if (producto.imagen_1) {
            return this.generateImageUrl(producto.imagen_1);
        }

        // Imagen por defecto
        return '/producto.jpg';
    },

    generateImageUrl(path) {
        // Usar URL absoluta en producción
        if (process.env.NODE_ENV === 'production') {
            return window.location.origin + '/storage/' + path;
        }
        return '/storage/' + path;
    },
                // Método para calcular el porcentaje y etapa de la garantía
                calcularPorcentajeGarantia() {
                    if (!this.garantia.fecha_venta || !this.garantia.fecha_Vencimiento) return 0;

                    const fechaInicio = new Date(this.garantia.fecha_venta);
                    const fechaFin = new Date(this.garantia.fecha_Vencimiento);
                    const hoy = new Date();

                    // Calcular días totales y restantes
                    const diasTotales = Math.ceil((fechaFin - fechaInicio) / (1000 * 60 * 60 * 24));
                    this.diasRestantes = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));

                    // Calcular porcentaje de tiempo RESTANTE (no transcurrido)
                    const porcentajeRestante = (this.diasRestantes / diasTotales) * 100;

                    // Determinar la etapa de la garantía
                    if (this.diasRestantes <= 0) {
                        this.warrantyStage = 'expired';
                    } else if (porcentajeRestante <= 20) {
                        this.warrantyStage = 'ending'; // Últimos 20% del tiempo
                    } else if (porcentajeRestante <= 50) {
                        this.warrantyStage = 'mid'; // Entre 20-50% del tiempo restante
                    } else {
                        this.warrantyStage = 'new'; // Más del 50% del tiempo restante
                    }

                    return Math.max(0, Math.min(100, porcentajeRestante));
                },

                // Método para obtener las clases CSS según la etapa
                getWarrantyStageClass() {
                    return {
                        'new-stage': this.warrantyStage === 'new',
                        'mid-stage': this.warrantyStage === 'mid',
                        'ending-stage': this.warrantyStage === 'ending',
                        'expired-stage': this.warrantyStage === 'expired',
                        'progress-bar-animated': this.warrantyStage === 'ending',
                        'progress-bar-striped': this.warrantyStage === 'ending'
                    };
                },

                // Mostrar conteo de días solo cuando está por vencer
                showDaysCount() {
                    return this.warrantyStage === 'ending' || this.warrantyStage === 'mid';
                },

                // Métodos existentes
                Buscar() {
                    this.errors = [];
                    this.garantia = [];

                    if (this.search.length == 11) {
                        this.loading = true;
                        urlBuscar = (serie_id !== '') ? '../garantia/buscar' : 'garantia/buscar';
                        axios.post(urlBuscar, {
                            search: this.search,
                        }).then(response => {
                            this.loading = false;
                            this.state = response.data.state;
                            this.garantia = response.data.garantia;
                            this.calcularPorcentajeGarantia();
                        }).catch(error => {
                            this.loading = false;
                            alert("Ocurrio un error al buscar, por favor intente nuevamente.");
                        });
                    } else {
                        this.errors['search'] = ['El codigo debe ser de 11 caracteres.'];
                    }
                },
                Fecha(doc) {
                    let date = new Date(doc)
                    let day = this.zeroFill(date.getDate(), 2)
                    let month = date.getMonth() + 1
                    let year = date.getFullYear()
                    let hour = date.getHours()
                    let min = this.zeroFill(date.getMinutes(), 2);

                    hour = this.zeroFill(hour, 2);

                    if (month < 10) {
                        return (`${day}-0${month}-${year} ${hour}:${min}`)
                    } else {
                        return (`${day}-${month}-${year} ${hour}:${min}`)
                    }
                },
                zeroFill(number, width) {
                    width -= number.toString().length;
                    if (width > 0) {
                        return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
                    }
                    return number + "";
                },
            },
        });
    </script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
@endsection
