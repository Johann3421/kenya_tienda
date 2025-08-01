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
        .warranty-progress {
            margin: 20px 0;
        }

        .progress {
            height: 25px;
            border-radius: 12px;
            overflow: hidden;
            background-color: #f5f5f5;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            transition: width 0.6s ease;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        /* Colores por etapa */
        .new-stage {
            background-color: #4CAF50;
            /* Verde - Nueva */
        }

        .mid-stage {
            background-color: #FFC107;
            /* Amarillo - Intermedia */
        }

        .ending-stage {
            background-color: #F44336;
            /* Rojo - Por vencer */
            animation: pulse 1.5s infinite;
        }

        .expired-stage {
            background-color: #9E9E9E;
            /* Gris - Vencida */
        }

        .progress-text {
            background: rgba(0, 0, 0, 0.3);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
        }

        .warranty-stage-info {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 12px;
        }

        .warranty-stage-info span {
            color: #aaa;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .warranty-stage-info span.active {
            font-weight: bold;
        }

        .warranty-stage-info span.active:nth-child(1) {
            color: #4CAF50;
        }

        .warranty-stage-info span.active:nth-child(2) {
            color: #FF9800;
        }

        .warranty-stage-info span.active:nth-child(3) {
            color: #F44336;
        }

        .warranty-stage-info span.active:nth-child(4) {
            color: #9E9E9E;
        }

        .expiration-message {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }

        .expiration-message.new {
            background-color: #E8F5E9;
            color: #2E7D32;
        }

        .expiration-message.mid {
            background-color: #FFF3E0;
            color: #EF6C00;
        }

        .expiration-message.ending {
            background-color: #FFEBEE;
            color: #C62828;
            animation: pulse 2s infinite;
        }

        .expiration-message.expired {
            background-color: #F5F5F5;
            color: #616161;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

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

            margin-bottom: 12px;
            color: #6c757d;

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

        /* Centra el PDF y limita el ancho */
        .pdf-center-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            min-height: 400px;
        }

        #pdf-viewer canvas {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        /* Opcional: Limita el ancho máximo del PDF */
        #pdf-viewer {
            max-width: 800px;
            width: 100%;
        }
    </style>
@endsection
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catalogo</a></li>
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
                            <a class="nav-link" :class="{ disabled: !tabsEnabled }" id="drivers-tab" data-toggle="tab"
                                href="#drivers" role="tab">
                                <i class="bx bx-chip"></i> Controladores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ disabled: !tabsEnabled }" id="gallery-tab" data-toggle="tab"
                                href="#gallery" role="tab">
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
                            <div class="search-container">
                                <div class="search-box">
                                    <div class="input-group modern-input-group">
                                        <input type="text" v-model="search" class="form-control modern-input"
                                            placeholder="Ingrese Número de Serie" v-on:keyup.enter="Buscar" maxlength="14"
                                            :class="[errors.search ? 'is-invalid' : '']" style="text-transform: uppercase;">
                                        <div class="input-group-append">
                                            <button class="btn search-button" type="button" v-on:click="Buscar">
                                                <i class="bx bx-search"></i> Buscar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="error-message" v-if="errors.search">@{{ errors.search[0] }}</div>
                                </div>
                            </div>

                            <div class="results-container">
                                <div v-if="loading" class="loading-container">
                                    <div class="modern-spinner"></div>
                                    <span class="loading-text">Buscando garantía...</span>
                                </div>

                                <div v-if="state">
                                    <div v-if="state == 'success'">
                                        <div class="warranty-card">
                                            <div class="card-header">
                                                <div class="product-title" v-for="nom in garantia.get_productos">
                                                    Producto: @{{ nom.nombre }}
                                                </div>
                                            </div>

                                            <div class="card-content">
                                                <!-- Columna de Detalles -->
                                                <div class="details-column">
                                                    <div class="section-title">
                                                        <iconify-icon icon="zondicons:align-center"></iconify-icon> DETALLES
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="fa-solid fa-tv"></i> Serie: @{{ garantia.serie }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <iconify-icon icon="bx:calendar"></iconify-icon> Inicia:
                                                        @{{ garantia.fecha_venta }}
                                                    </div>
                                                    <div class="detail-item">
                                                        <iconify-icon icon="bx:time"></iconify-icon> Garantía:
                                                        @{{ garantia.garantia }} Meses
                                                    </div>

                                                    <div class="product-image-container">
                                                        <img v-for="producto in garantia.get_productos"
                                                            :src="(producto.modelo && (producto.modelo.id == 10 ||
                                                                (producto.modelo.descripcion &&
                                                                    producto.modelo.descripcion.toLowerCase()
                                                                    .includes('tonner')))) ?
                                                            (producto.imagen_1 ? '/storage/' + producto.imagen_1 :
                                                                '/producto.jpg') :
                                                            (producto.modelo && producto.modelo.img_mod ?
                                                                '/storage/' + producto.modelo.img_mod :
                                                                '/producto.jpg')"
                                                            class="product-image" :alt="'Imagen de ' + producto.nombre">
                                                    </div>

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
                                                            Garantía en etapa intermedia - @{{ diasRestantes }} días
                                                            restantes (Vence: @{{ garantia.fecha_Vencimiento }})
                                                        </span>
                                                        <span v-else-if="warrantyStage === 'ending'">
                                                            ¡Garantía por vencer! - Solo @{{ diasRestantes }} días
                                                            restantes (Vence: @{{ garantia.fecha_Vencimiento }})
                                                        </span>
                                                        <span v-else-if="warrantyStage === 'expired'">
                                                            Garantía vencida el @{{ garantia.fecha_Vencimiento }}
                                                        </span>
                                                    </div>

                                                    <div class="expiration-message expired"
                                                        v-if="garantia.fecha_Vencimiento < vencido">
                                                        <iconify-icon icon="bx:calendar"></iconify-icon>
                                                        La garantía ha vencido el: @{{ garantia.fecha_Vencimiento }}
                                                    </div>
                                                    <div class="expiration-message expiring"
                                                        v-if="garantia.fecha_Vencimiento == vencido">
                                                        <iconify-icon icon="bx:calendar"></iconify-icon>
                                                        La Garantía vence hoy
                                                    </div>
                                                </div>

                                                <!-- Columna de Especificaciones -->
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

                                    <div v-else class="no-results">
                                        No se encontró garantía para <strong>@{{ search }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pestañas restantes se mantienen igual -->
                        <div class="tab-pane fade" id="drivers" role="tabpanel">
                            <div class="drivers-container" v-if="state == 'success'">
                                <div class="section-title">
                                    <i class="bx bx-chip"></i> CONTROLADORES DISPONIBLES
                                </div>
                                <div class="drivers-grid">
    <div v-for="drivers in filteredDrivers" class="driver-card">
        <div class="driver-name">@{{ drivers.nombre }}</div>
        <a :href="'../storage/' + drivers.link" target="_blank" class="driver-download">
            <iconify-icon icon="bx:download"></iconify-icon> Descargar
        </a>
    </div>
</div>
                            </div>
                            <div v-else class="no-data">
                                <i class="bx bx-search-alt"></i> Ingrese un número de serie válido para ver los
                                controladores
                            </div>
                        </div>

                        <div class="tab-pane fade" id="gallery" role="tabpanel">
                            <div v-if="state == 'success'" class="video-gallery">
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
                            <div v-else class="no-data">
                                <i class="bx bx-search-alt"></i> Ingrese un número de serie válido para ver la galería de
                                videos
                            </div>
                        </div>

                        <div class="tab-pane fade" id="terms" role="tabpanel">
                            <div class="pdf-center-container">
                                <div id="pdf-viewer"></div>
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
    </script>
    <script>
        n = new Date();
        //Año
        y = n.getFullYear();
        //Mes
        m = n.getMonth() + 1;
        //Día
        d = n.getDate();

        //Lo ordenas a gusto.
        // let date = document.getElementById("date")
        // date.textContent = d;
    </script>
    <script src="{{ asset('js/consultar/garantia.js') }}"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
    <script src="{{ asset('js/pdfjs/pdf.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function renderPDF() {
                if (!window.pdfjsLib) return;
                pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('js/pdfjs/pdf.worker.js') }}";
                const url = "{{ asset('GARANTIA_KENYA_SIN_HORARIO.pdf') }}";
                const container = document.getElementById('pdf-viewer');
                container.innerHTML = '';
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        pdf.getPage(pageNum).then(function(page) {
                            const viewport = page.getViewport({
                                scale: 1.2
                            });
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            container.appendChild(canvas);
                            page.render({
                                canvasContext: context,
                                viewport: viewport
                            });
                        });
                    }
                });
            }

            // Renderiza el PDF cada vez que se hace clic en la pestaña de términos
            document.getElementById('terms-tab').addEventListener('click', function() {
                setTimeout(renderPDF, 100); // Espera a que la pestaña sea visible
            });

            // Si la pestaña está activa al cargar, renderiza de inmediato
            if (document.getElementById('terms').classList.contains('active') ||
                document.getElementById('terms').classList.contains('show')) {
                renderPDF();
            }
        });
    </script>
@endsection
