@extends('layouts.landing')
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
@section('css')
    <style>
        #garantia { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; }
        #garantia .container { max-width: 1400px; margin: 0 auto; width: 100%; padding: 0; }

        #garantia .support-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('/landing/img/slide/slide-1.jpg') center/cover no-repeat;
            padding: 90px 20px; text-align: left;
        }
        #garantia .support-hero h1 { font-size: 2.8rem; color: #000; font-weight: 400; margin-bottom: -3px; }
        #garantia .support-hero p { font-size: 1.3rem; color: #000; font-weight: 300; max-width: 800px; margin: 0; }

        #garantia .support-nav-container { background: #fff; width: 100%; border-bottom: 1px solid #ccc; }
        #garantia .support-nav { display: flex; justify-content: left; max-width: 1200px; margin: 0 240px; padding: 0; list-style: none; flex-wrap: wrap; }
        #garantia .support-nav li a {
            display: flex; align-items: center; border-radius: 100px; gap: 10px; padding: 15px 30px;
            color: #333; text-decoration: none; font-weight: 600; font-size: 1.05rem; transition: background-color 0.3s; cursor: pointer;
        }
        #garantia .support-nav li a.active { background: linear-gradient(to right, #ff3c00, #ff9c00); color: #fff; }
        #garantia .support-nav li a:hover:not(.active) { background-color: #eaeaea; }

        #garantia .warranty-search-section { text-align: center; padding: 40px 20px; }
        #garantia .warranty-search-section p { margin-bottom: 15px; color: #444; font-size: 0.95rem; }
        #garantia .search-box { display: flex; justify-content: center; max-width: 600px; margin: 0 auto; gap: 10px; }
        #garantia .search-box input {
            flex-grow: 1; padding: 12px 20px; border: 1px solid #ccc; border-radius: 100px;
            font-size: 1rem; color: #666; outline: none;
        }
        #garantia .search-box input:focus { border-color: #f26522; }
        #garantia .search-box button {
            background: linear-gradient(to right, #ff3c00, #ff9c00); color: white; border: none;
            padding: 12px 35px; font-size: 1.2rem; font-weight: bold; border-radius: 100px; cursor: pointer; transition: background-color 0.3s;
        }
        #garantia .search-box button:hover { background-color: #d9531e; }

        #garantia #main-results-container { max-width: 1200px; margin: 0 auto 40px; }

        #garantia .result-card { background: #fff; border-radius: 16px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); display: flex; overflow: hidden; }
        #garantia .result-image { background-color: #eee; width: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0; }
        #garantia .result-image img { max-width: 100%; height: auto; display: block; object-fit: cover; }
        #garantia .result-details { padding: 25px; flex-grow: 1; }
        #garantia .result-title { color: #555; font-size: 1.1rem; font-weight: 600; }
        #garantia .result-subtitle { color: #4b4b4b; font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; }
        #garantia .details-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-bottom: 25px; }
        #garantia .details-col h4 { color: #f26522; margin-bottom: 6px; font-size: 1rem; font-weight: 600; }
        #garantia .details-col ul { list-style: none; }
        #garantia .details-col ul li { font-size: 0.95rem; margin-bottom: 0; color: #444; display: flex; align-items: center; gap: 10px; }
        #garantia .details-col.specs ul li::before { content: '\25B6'; font-size: 0.6rem; color: #666; }

        #garantia .progress-section { margin: 25px 0; }
        #garantia .progress-bar-container { width: 100%; height: 20px; background-color: #e5e7eb; border-radius: 4px; overflow: hidden; margin-bottom: 10px; }
        #garantia .progress-bar-fill { height: 100%; background-color: #f26522; width: 40%; transition: width 0.6s ease; }
        #garantia .progress-labels { display: flex; justify-content: space-between; font-size: 0.75rem; color: #555; padding: 0 5px; }
        #garantia .progress-labels span { display: flex; align-items: center; gap: 5px; }

        #garantia .bottom-info { display: flex; justify-content: space-between; align-items: flex-end; padding-top: 0; }
        #garantia .warranty-date { font-weight: bold; color: #333; font-size: 0.95rem; }
        #garantia .warranty-link { font-size: 0.7rem; color: #0066cc; text-decoration: none; display: block; margin-top: 0; cursor: pointer; }
        #garantia .action-buttons { display: flex; gap: 15px; }
        #garantia .btn-outline {
            border: 1px solid #f26522; color: #f26522; background: transparent; padding: 6px 15px;
            font-size: 0.85rem; border-radius: 100px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.3s;
        }
        #garantia .btn-outline:hover { background: #f26522; color: #fff; }

        #garantia .progress-bar-fill.new-stage { background-color: #4CAF50; }
        #garantia .progress-bar-fill.mid-stage { background-color: #FFC107; }
        #garantia .progress-bar-fill.ending-stage { background-color: #F44336; animation: pulse 1.5s infinite; }
        #garantia .progress-bar-fill.expired-stage { background-color: #9E9E9E; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }

        #garantia .stage-labels { display: flex; justify-content: center; flex-wrap: wrap; gap: 8px; margin-top: 14px; font-size: 12px; }
        #garantia .stage-labels span {
            color: #aaa; background-color: #f4f4f4; padding: 6px 14px; border-radius: 20px;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; display: inline-flex; align-items: center; gap: 4px;
        }
        #garantia .stage-labels span.active { font-weight: bold; color: #fff; }
        #garantia .stage-labels span.active:nth-child(1) { background-color: #9E9E9E; }
        #garantia .stage-labels span.active:nth-child(2) { background-color: #F44336; }
        #garantia .stage-labels span.active:nth-child(3) { background-color: #FFC107; }
        #garantia .stage-labels span.active:nth-child(4) { background-color: #4CAF50; }

        #garantia .drivers-container { display: none; background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        #garantia .drivers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        #garantia .driver-card {
            padding: 35px 20px; text-align: center; display: flex; flex-direction: column;
            align-items: center; justify-content: center; min-height: 140px; position: relative;
            background: #f9f9f9; border-radius: 8px; transition: all 0.3s ease;
        }
        #garantia .driver-card:hover { border-color: #e0e0e0; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        #garantia .driver-card i { font-size: 2.2rem; color: #333; margin-bottom: 15px; }
        #garantia .driver-card span { font-size: 0.95rem; color: #222; font-weight: 500; }

        #garantia .video-gallery-container { display: none; background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); }
        #garantia .video-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        #garantia .video-card { background: #fdfdfd; border-radius: 8px; overflow: hidden; transition: box-shadow 0.3s ease; display: flex; flex-direction: column; }
        #garantia .video-card:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        #garantia .video-thumbnail { width: 100%; aspect-ratio: 16/9; position: relative; background-color: #000; }
        #garantia .video-thumbnail iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; }
        #garantia .video-details { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        #garantia .video-title { color: #333; font-size: 0.9rem; line-height: 1.4; }

        #garantia .terms-container { display: none; background: #fff; border-radius: 16px; box-shadow: 0 5px 25px rgba(0,0,0,0.05); overflow: hidden; }
        #garantia .terms-layout { display: flex; min-height: 500px; }
        #garantia .terms-sidebar { width: 280px; background-color: #f9f9f9; padding: 30px; border-right: 1px solid #eaeaea; flex-shrink: 0; }
        #garantia .terms-sidebar h4 { color: #333; font-size: 1.1rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f26522; }
        #garantia .terms-nav { list-style: none; }
        #garantia .terms-nav li { margin-bottom: 12px; }
        #garantia .terms-nav a { text-decoration: none; color: #555; font-size: 0.95rem; transition: color 0.3s; display: block; cursor: pointer; }
        #garantia .terms-nav a:hover, #garantia .terms-nav a.active-term { color: #f26522; font-weight: 600; padding-left: 5px; }
        #garantia .terms-content { padding: 40px; flex-grow: 1; max-width: 900px; }
        #garantia .terms-content h2 { font-size: 1.8rem; color: #222; margin-bottom: 5px; }

        #garantia .pdf-controls { display: flex; gap: 10px; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 4px; flex-wrap: wrap; align-items: center; }
        #garantia .pdf-btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; }
        #garantia .pdf-btn-primary { background: #f26522; color: #fff; }
        #garantia .pdf-btn-success { background: #28a745; color: #fff; }
        #garantia .pdf-zoom-level { font-weight: 600; color: #333; margin-left: 10px; }
        #garantia .pdf-center-container { text-align: center; }
        #garantia #pdf-viewer canvas { margin: 5px auto; max-width: 100%; height: auto !important; }
        #garantia .pdf-fullscreen-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; }
        #garantia .pdf-fullscreen-modal.active { display: flex; align-items: center; justify-content: center; }
        #garantia .pdf-fullscreen-content { width: 95%; height: 95%; background: #fff; border-radius: 8px; display: flex; flex-direction: column; overflow: hidden; }
        #garantia .pdf-fullscreen-header { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #f8f9fa; border-bottom: 1px solid #ddd; }
        #garantia .pdf-fullscreen-header h3 { margin: 0; }
        #garantia .pdf-close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #333; }
        #garantia .pdf-fullscreen-viewer { flex: 1; overflow: auto; padding: 20px; text-align: center; }
        #garantia #pdf-fullscreen-container canvas { margin: 5px auto; max-width: 100%; }

        #garantia .loading-container { display: flex; flex-direction: column; align-items: center; padding: 2rem; }
        #garantia .modern-spinner { width: 50px; height: 50px; border: 4px solid rgba(242,101,34,0.3); border-radius: 50%; border-top-color: #f26522; animation: spin 1s ease-in-out infinite; margin-bottom: 1rem; }
        @keyframes spin { to { transform: rotate(360deg); } }
        #garantia .no-data, #garantia .no-results { text-align: center; padding: 3rem; color: #999; font-size: 1rem; }

        @media (max-width: 992px) {
            #garantia .support-hero h1 { font-size: 2rem; }
            #garantia .support-hero p { font-size: 1.1rem; }
            #garantia .result-card { flex-direction: column; }
            #garantia .result-image { width: 100%; padding: 30px; }
            #garantia .details-grid { grid-template-columns: 1fr; }
            #garantia .bottom-info { flex-direction: column; align-items: flex-start; gap: 20px; }
            #garantia .video-grid { grid-template-columns: repeat(2, 1fr); }
            #garantia .terms-layout { flex-direction: column; }
            #garantia .terms-sidebar { width: 100%; border-right: none; border-bottom: 1px solid #eaeaea; }
            #garantia .terms-content { padding: 25px; }
            #garantia .support-nav { margin: 0 20px; }
            #garantia .support-nav li a { padding: 12px 20px; font-size: 0.95rem; }
        }
        @media (max-width: 768px) {
            #garantia .support-nav { justify-content: center; margin: 0 10px; }
            #garantia .support-nav li a { padding: 10px 16px; font-size: 0.85rem; gap: 6px; }
        }
        @media (max-width: 576px) {
            #garantia .support-hero { padding: 55px 16px; }
            #garantia .support-hero h1 { font-size: 1.5rem; }
            #garantia .support-hero p { font-size: 0.95rem; }
            #garantia .search-box { flex-direction: column; align-items: stretch; }
            #garantia .search-box button { width: 100%; }
            #garantia .video-grid { grid-template-columns: 1fr; }
        }
    </style>
@endsection
@section('content')
<div id="garantia">
    <section class="support-hero">
        <div class="container">
            <h1>Bienvenido al centro de soporte Kenya</h1>
            <p>Controladores, actualizaciones, guías prácticas, ayuda técnica y más</p>
        </div>
    </section>

    <div class="support-nav-container">
        <ul class="support-nav">
            <li><a class="support-tab active" data-target="tab-garantia"><i class="fa-solid fa-certificate"></i> Garantía</a></li>
            <li><a class="support-tab" data-target="tab-controladores"><i class="fa-solid fa-gear"></i> Controladores</a></li>
            <li><a class="support-tab" data-target="tab-galeria"><i class="fa-brands fa-youtube"></i> Galería de videos</a></li>
            <li><a class="support-tab" data-target="tab-terminos"><i class="fa-solid fa-file-invoice"></i> Términos y condiciones</a></li>
        </ul>
    </div>

    <section class="warranty-search-section">
        <p>Identifique su producto para obtener información sobre el estado de su garantía o descargar controladores.</p>
        <div class="search-box">
            <input type="text" v-model="search" placeholder="Ingrese su número de serie" maxlength="14">
            <button v-on:click="Buscar">Buscar</button>
        </div>
        <p v-if="errors.search" style="color: #f26522; margin-top: 15px; font-weight: 600;">@{{ errors.search[0] }}</p>
    </section>

    <div id="main-results-container" style="display: none;" v-show="state != null">
        <div v-if="loading" class="loading-container">
            <div class="modern-spinner"></div>
            <p style="font-size: 1.25rem; color: #6c757d;">Buscando...</p>
        </div>

        <div v-if="state == 'success' && !loading">
            <article class="result-card">
                <div class="result-image">
                    <img src="{{ asset('producto.jpg') }}" alt="Producto">
                </div>
                <div class="result-details">
                    <h3 class="result-title">Estado del Producto</h3>
                    <h2 class="result-subtitle">@{{ garantia.get_productos && garantia.get_productos[0] ? garantia.get_productos[0].nombre + ' (' + garantia.serie + ')' : garantia.serie }}</h2>

                    <div class="details-grid">
                        <div class="details-col">
                            <h4>Detalles</h4>
                            <ul>
                                <li><i class="fa-solid fa-barcode"></i> <strong>Serie:</strong> @{{ garantia.serie }}</li>
                                <li><i class="fa-regular fa-calendar-days"></i> <strong>Inicia:</strong> @{{ garantia.fecha_venta }}</li>
                                <li><i class="fa-regular fa-clock"></i> <strong>Garantía:</strong> @{{ garantia.garantia }} meses</li>
                            </ul>
                        </div>
                        <div class="details-col specs">
                            <h4>Especificaciones</h4>
                            <ul>
                                <li v-if="garantia.get_productos && garantia.get_productos[0] && garantia.get_productos[0].procesador">@{{ garantia.get_productos[0].procesador }}</li>
                                <li v-if="garantia.get_productos && garantia.get_productos[0] && garantia.get_productos[0].ram">@{{ garantia.get_productos[0].ram }}</li>
                                <li v-if="garantia.get_productos && garantia.get_productos[0] && garantia.get_productos[0].almacenamiento">@{{ garantia.get_productos[0].almacenamiento }}</li>
                                <li v-if="garantia.get_productos && garantia.get_productos[0] && garantia.get_productos[0].sistema_operativo">@{{ garantia.get_productos[0].sistema_operativo }}</li>
                                <li v-if="garantia.get_productos && garantia.get_productos[0] && garantia.get_productos[0].suite_ofimatica">@{{ garantia.get_productos[0].suite_ofimatica }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="progress-section">
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" :class="warrantyStageClass" :style="'width:' + porcentajeGarantia + '%'"></div>
                        </div>
                        <div class="progress-labels">
                            <span><i class="fa-regular fa-circle-xmark"></i> Vencida</span>
                            <span><i class="fa-solid fa-circle-exclamation"></i> Por vencer</span>
                            <span><i class="fa-regular fa-clock"></i> Intermedia</span>
                            <span><i class="fa-regular fa-circle-check"></i> Nueva</span>
                        </div>
                        <div class="stage-labels">
                            <span :class="{ 'active': warrantyStage === 'expired' }"><i class="fa-regular fa-circle-xmark"></i> Vencida</span>
                            <span :class="{ 'active': warrantyStage === 'ending' }"><i class="fa-solid fa-circle-exclamation"></i> Por vencer</span>
                            <span :class="{ 'active': warrantyStage === 'mid' }"><i class="fa-regular fa-clock"></i> Intermedia</span>
                            <span :class="{ 'active': warrantyStage === 'new' }"><i class="fa-regular fa-circle-check"></i> Nueva</span>
                        </div>
                    </div>

                    <div class="bottom-info">
                        <div>
                            <div class="warranty-date"><i class="fa-regular fa-calendar-check"></i> La garantía vence: @{{ garantia.fecha_Vencimiento }}</div>
                            <a class="warranty-link" href="#">&gt; Términos y condiciones de la garantía.</a>
                        </div>
                        <div class="action-buttons" v-if="garantia.get_productos && garantia.get_productos[0]">
                            <a :href="'../storage/' + garantia.get_productos[0].ficha_tecnica" target="_blank" class="btn-outline" v-if="garantia.get_productos[0].ficha_tecnica">
                                <i class="fa-solid fa-download"></i> Ficha Técnica
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <div class="drivers-container" style="display: block; margin-top: 30px;">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-microchip"></i> CONTROLADORES</h3>
                <div v-if="filteredDrivers.length > 0" class="drivers-grid">
                    <div v-for="driver in filteredDrivers" class="driver-card">
                        <i class="fa-solid fa-download"></i>
                        <span>@{{ driver.nombre }}</span>
                        <a :href="'../storage/' + driver.link" target="_blank" class="btn-outline" style="margin-top: 10px;"><i class="fa-solid fa-download"></i> Descargar</a>
                    </div>
                </div>
                <div v-else class="no-data"><i class="fa-solid fa-search"></i> Sin controladores disponibles para este producto</div>
            </div>
        </div>

        <div v-else-if="state == 'error' && !loading" class="no-results">
            No se encontró garantía para <strong>@{{ search }}</strong>
        </div>
    </div>

    <div class="video-gallery-container" id="tab-galeria-content" style="display: none; max-width: 1200px; margin: 0 auto 40px;">
        <h3 style="margin-bottom: 20px;"><i class="fa-brands fa-youtube"></i> GALERÍA DE VIDEOS</h3>
        <div class="video-grid">
            <div class="video-card">
                <div class="video-thumbnail">
                    <iframe src="https://www.youtube.com/embed/mFswNoideic" title="YouTube" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-details">
                    <h4 class="video-title">Embalaje de piezas de servicio de unidades reemplazables Kenya</h4>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumbnail">
                    <iframe src="https://www.youtube.com/embed/mFswNoideic" title="YouTube" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-details">
                    <h4 class="video-title">Cómo encontrar la información de su garantía Kenya</h4>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumbnail">
                    <iframe src="https://www.youtube.com/embed/mFswNoideic" title="YouTube" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-details">
                    <h4 class="video-title">Cómo localizar un centro de reparación Kenya</h4>
                </div>
            </div>
            <div class="video-card">
                <div class="video-thumbnail">
                    <iframe src="https://www.youtube.com/embed/mFswNoideic" title="YouTube" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-details">
                    <h4 class="video-title">Recorriendo la BIOS UEFI Kenya</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="terms-container" id="tab-terminos-content" style="display: none; max-width: 1200px; margin: 0 auto 40px;">
        <div class="terms-layout">
            <aside class="terms-sidebar">
                <h4>Índice de Políticas</h4>
                <ul class="terms-nav">
                            <li><a class="active-term" href="#">1. Vigencia de la garantía</a></li>
                            <li><a href="#">2. Información general</a></li>
                            <li><a href="#">3. Condiciones</a></li>
                            <li><a href="#">4. Exclusiones</a></li>
                </ul>
            </aside>
            <div class="terms-content">
                <div id="pdf-controls" class="pdf-controls">
                    <button id="pdf-zoom-in" class="pdf-btn pdf-btn-primary"><i class="bx bx-plus"></i> Zoom +</button>
                    <button id="pdf-zoom-out" class="pdf-btn pdf-btn-primary"><i class="bx bx-minus"></i> Zoom -</button>
                    <button id="pdf-fullscreen" class="pdf-btn pdf-btn-success"><i class="bx bx-fullscreen"></i> Pantalla Completa</button>
                    <span id="pdf-zoom-level" class="pdf-zoom-level">100%</span>
                </div>
                <div class="pdf-center-container"><div id="pdf-viewer"></div></div>
            </div>
        </div>
    </div>

    <div id="pdf-fullscreen-modal" class="pdf-fullscreen-modal">
        <div class="pdf-fullscreen-content">
            <div class="pdf-fullscreen-header">
                <h3>Términos y Condiciones</h3>
                <button id="pdf-close-fullscreen" class="pdf-close-btn"><i class="bx bx-x"></i></button>
            </div>
            <div class="pdf-fullscreen-controls" style="padding: 10px 20px; display: flex; gap: 10px; align-items: center; border-bottom: 1px solid #ddd;">
                <button id="pdf-fullscreen-zoom-in" class="pdf-btn pdf-btn-primary"><i class="bx bx-plus"></i></button>
                <button id="pdf-fullscreen-zoom-out" class="pdf-btn pdf-btn-primary"><i class="bx bx-minus"></i></button>
                <span id="pdf-fullscreen-zoom-level" class="pdf-zoom-level">100%</span>
            </div>
            <div class="pdf-fullscreen-viewer"><div id="pdf-fullscreen-container"></div></div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        var my_whatsapp = {!! json_encode($whatsapp) !!};
        var mi_fecha = {!! json_encode(date('Y-m-d')) !!};
        // Configure Axios CSRF
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken && window.axios) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        }
    </script>
    <script src="{{ asset('js/consultar/garantia.js') }}?v=2"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.0/iconify-icon.min.js"></script>
    <script src="{{ asset('js/pdfjs/pdf.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            document.querySelectorAll('.support-tab').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.support-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const target = this.getAttribute('data-target');
                    if (target === 'tab-garantia') {
                        document.getElementById('main-results-container').style.display = '';
                        document.getElementById('tab-galeria-content').style.display = 'none';
                        document.getElementById('tab-terminos-content').style.display = 'none';
                    } else if (target === 'tab-controladores') {
                        document.getElementById('main-results-container').style.display = '';
                        document.getElementById('tab-galeria-content').style.display = 'none';
                        document.getElementById('tab-terminos-content').style.display = 'none';
                        // scroll to drivers section
                        const drivers = document.querySelector('.drivers-container');
                        if (drivers) drivers.scrollIntoView({ behavior: 'smooth' });
                    } else if (target === 'tab-galeria') {
                        document.getElementById('main-results-container').style.display = 'none';
                        document.getElementById('tab-galeria-content').style.display = 'block';
                        document.getElementById('tab-terminos-content').style.display = 'none';
                    } else if (target === 'tab-terminos') {
                        document.getElementById('main-results-container').style.display = 'none';
                        document.getElementById('tab-galeria-content').style.display = 'none';
                        document.getElementById('tab-terminos-content').style.display = 'block';
                        renderMainPDF();
                        pdfRendered = true;
                    }
                });
            });

            // Switch to terms tab from warranty link
            const switchTab = function(tab) {
                if (tab === 'terminos') {
                    document.querySelector('.support-tab[data-target="tab-terminos"]').click();
                }
            };
            document.querySelectorAll('.warranty-link').forEach(el => {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector('.support-tab[data-target="tab-terminos"]').click();
                });
            });

            // PDF
            let pdfRendered = false;
            let currentScale = 1.0;
            let fullscreenScale = 1.0;

            function getOptimalPDFScale() {
                const w = window.innerWidth;
                if (w <= 480) return 1.0;
                if (w <= 576) return 1.0;
                if (w <= 768) return 1.8;
                if (w <= 992) return 1.5;
                return 1.2;
            }

            currentScale = getOptimalPDFScale();
            fullscreenScale = currentScale;

            function renderPDF(containerId, scale) {
                if (!window.pdfjsLib) return;
                pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('js/pdfjs/pdf.worker.js') }}";
                const url = "{{ asset('GARANTIA_KENYA_SIN_HORARIO.pdf') }}";
                const container = document.getElementById(containerId);
                if (!container) return;
                container.innerHTML = '';
                const renderScale = scale !== undefined ? scale : currentScale;
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        pdf.getPage(pageNum).then(function(page) {
                            const viewport = page.getViewport({ scale: renderScale });
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            canvas.style.maxWidth = '100%';
                            canvas.style.height = 'auto';
                            container.appendChild(canvas);
                            page.render({ canvasContext: ctx, viewport: viewport });
                        });
                    }
                });
            }

            function renderMainPDF(scale) { renderPDF('pdf-viewer', scale); }
            function renderFullscreenPDF(scale) { renderPDF('pdf-fullscreen-container', scale); }

            function updateZoom(scale, el) {
                el.textContent = Math.round(scale * 100) + '%';
            }

            const zoomIn = document.getElementById('pdf-zoom-in');
            const zoomOut = document.getElementById('pdf-zoom-out');
            const zoomLevel = document.getElementById('pdf-zoom-level');
            const fullscreenBtn = document.getElementById('pdf-fullscreen');
            const modal = document.getElementById('pdf-fullscreen-modal');
            const closeBtn = document.getElementById('pdf-close-fullscreen');
            const fsZoomIn = document.getElementById('pdf-fullscreen-zoom-in');
            const fsZoomOut = document.getElementById('pdf-fullscreen-zoom-out');
            const fsZoomLevel = document.getElementById('pdf-fullscreen-zoom-level');

            if (zoomLevel) updateZoom(currentScale, zoomLevel);

            if (zoomIn) zoomIn.addEventListener('click', function() {
                currentScale = Math.min(currentScale + 0.2, 3.0);
                renderMainPDF(currentScale);
                updateZoom(currentScale, zoomLevel);
            });
            if (zoomOut) zoomOut.addEventListener('click', function() {
                currentScale = Math.max(currentScale - 0.2, 0.5);
                renderMainPDF(currentScale);
                updateZoom(currentScale, zoomLevel);
            });
            if (fullscreenBtn) fullscreenBtn.addEventListener('click', function() {
                modal.classList.add('active');
                fullscreenScale = currentScale;
                if (fsZoomLevel) updateZoom(fullscreenScale, fsZoomLevel);
                setTimeout(() => renderFullscreenPDF(fullscreenScale), 100);
            });
            if (closeBtn) closeBtn.addEventListener('click', function() { modal.classList.remove('active'); });
            if (modal) modal.addEventListener('click', function(e) { if (e.target === modal) modal.classList.remove('active'); });
            document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && modal?.classList.contains('active')) modal.classList.remove('active'); });
            if (fsZoomIn) fsZoomIn.addEventListener('click', function() {
                fullscreenScale = Math.min(fullscreenScale + 0.2, 4.0);
                renderFullscreenPDF(fullscreenScale);
                if (fsZoomLevel) updateZoom(fullscreenScale, fsZoomLevel);
            });
            if (fsZoomOut) fsZoomOut.addEventListener('click', function() {
                fullscreenScale = Math.max(fullscreenScale - 0.2, 0.5);
                renderFullscreenPDF(fullscreenScale);
                if (fsZoomLevel) updateZoom(fullscreenScale, fsZoomLevel);
            });

            let resizeTO;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTO);
                resizeTO = setTimeout(function() { if (pdfRendered) renderMainPDF(currentScale); }, 300);
            });

            // Session storage auto-search
            setTimeout(() => {
                const seriedelStorage = sessionStorage.getItem('garantia_serie');
                const urlParams = new URLSearchParams(window.location.search);
                const serieUrl = urlParams.get('serie');
                const serie = seriedelStorage || serieUrl;
                if (serie) {
                    const input = document.querySelector('#garantia input');
                    if (input) {
                        const vueApp = document.querySelector('#garantia');
                        if (vueApp && vueApp.__vue__) {
                            vueApp.__vue__.search = serie;
                            vueApp.__vue__.Buscar();
                        }
                    }
                }
            }, 500);
        });
    </script>
@endsection