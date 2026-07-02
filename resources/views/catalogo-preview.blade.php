@extends('layouts.landing')

@section('title', 'CatÃ¡logo Preview')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detallemod.css') }}">
    <style>
        /* ==========================================
           FRANJA BANNER (NUEVA)
           ========================================== */
        .page-banner {
            position: relative;
            width: 100%;
            height: 284px; 
            background-color: #333;
            background-image: url('{{ asset('banercatalogo.png?v=2') }}'); 
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255 255 255 / 35%); 
            z-index: 1;
        }

        .page-banner .banner-content {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 0 20px;
        }

        .page-banner h1 {
            color: #000000;
            font-size: 2.2rem;
            font-weight: 400;
            margin: 0;
            letter-spacing: 1px;
            text-shadow: 0px 0px 0px rgba(0,0,0,0.0);
        }

        @media (max-width: 768px) {
            .page-banner {
                height: 100px;
            }
            .page-banner h1 {
                font-size: 1.5rem;
            }
        }

        /* ==========================================
           SECCIÃ“N DE CATÃLOGO
           ========================================== */
        .catalog-section {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
            padding: 40px 0;
            align-items: start;
        }

        .catalog-sidebar {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            position: static;
            top: 100px;
        }

        .filter-group {
            margin-bottom: 25px;
        }

        .filter-group label {
            display: block;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .custom-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #333;
            outline: none;
            appearance: none;
            background: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="10" viewBox="0 0 14 10"><path fill="%23333" d="M7 10L0 0h14z"/></svg>') no-repeat right 15px center;
            background-size: 10px;
            background-color: #fff;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .custom-select:focus {
            border-color: #f26522;
        }

        .filter-title {
            font-size: 1.1rem;
            color: #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-bottom: 3px solid #f26522;
            font-weight: 600;
        }

        /* ==========================================
           ESTILOS DEL ACORDEÃ“N DESPLEGABLE 
           ========================================== */
        .accordion-wrapper {
            margin-bottom: 8px;
        }

        .accordion-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #eaeaea;
            padding: 12px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            color: #444;
        }

        .accordion-item span {
            font-weight: 500;
            transition: color 0.2s, font-weight 0.2s;
        }

        .accordion-item i {
            color: #888;
            font-size: 0.8rem;
            transition: color 0.2s;
        }

        .accordion-item:hover {
            background-color: #eef0f2;
            border-color: #ddd;
        }

        .accordion-item.active {
            background-color: #f8f9fa;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
        }

        .accordion-item.active span {
            font-weight: 700;
            color: #000;
        }

        .accordion-item.active i {
            color: #333;
        }

        .accordion-content {
            display: none;
            border: 1px solid #eaeaea;
            border-top: none;
            border-radius: 0 0 6px 6px;
            padding: 12px;
            background-color: #fff;
            margin-top: 0; 
        }

        .accordion-content.open {
            display: block;
        }

        .accordion-search {
            margin-bottom: 12px;
        }

        .accordion-search input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #555;
            outline: none;
            transition: border-color 0.3s;
        }

        .accordion-search input:focus {
            border-color: #f26522; 
        }

        .accordion-options {
            max-height: 210px; 
            overflow-y: auto;
            padding-right: 8px;
        }

        .accordion-options::-webkit-scrollbar {
            width: 6px;
        }

        .accordion-options::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .accordion-options::-webkit-scrollbar-thumb {
            background: #cfd4d9;
            border-radius: 4px;
        }

        .accordion-options::-webkit-scrollbar-thumb:hover {
            background: #aab0b6;
        }

        .option-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 5px;
            border-bottom: 1px solid #f4f4f4;
            cursor: pointer;
            font-size: 0.85rem;
            color: #444;
            transition: background-color 0.2s;
        }

        .option-item:last-child {
            border-bottom: none;
        }

        .option-item:hover {
            background-color: #fcfcfc;
        }

        .option-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #f26522; 
            margin: 0;
        }

        .btn-clear {
            width: 100%;
            background-color: #727b84;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .btn-clear:hover {
            background-color: #5c646b;
        }

        /* --- Contenido Principal (Productos) --- */
        .catalog-main {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .catalog-main-search input {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .catalog-main-search input:focus {
            border-color: #f26522;
            box-shadow: 0 0 8px rgba(242, 101, 34, 0.15);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(253px, 1fr));
            gap: 12px;
        }

        .product-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #eaeaea;
            padding: 15px 15px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: #ddd;
        }

        .product-image-wrapper {
            position: relative;
            text-align: center;
            margin-bottom: 20px;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image-wrapper img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-logos {
            position: absolute;
            width: 100%;
            display: flex;
            justify-content: space-between;
            top: 50%;
            transform: translateY(-50%);
            padding: 0 10px;
            pointer-events: none;
        }

        .product-logos img {
            height: 25px;
            opacity: 0.8;
        }

        .product-title {
            font-size: 0.95rem;
            color: #333;
            margin-bottom: 10px;
            line-height: 1.4;
            font-weight: 700;
        }

        .product-pn {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 0px;
        }

        .product-stock {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 20px;
            flex-grow: 1; 
        }

        .product-stock span {
            color: #2ecca6; 
            font-weight: 700;
        }

        .btn-details {
            width: 100%;
            background-color: #f07b3f; 
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 100px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            text-transform: uppercase;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-details:hover {
            background-color: #d96225;
        }
        
        .btn-details:active {
            transform: scale(0.98);
        }

        /* ==========================================
           PAGINACIÃ“N
           ========================================== */
        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
            margin-bottom: 0px;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-item {
            display: flex;
            justify-content: center;
            align-items: center;
            min-width: 40px;
            height: 40px;
            padding: 0 10px;
            background-color: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            color: #777;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .page-item:hover:not(.dots) {
            border-color: #f26522;
            color: #f26522;
        }

        .page-item.active {
            background-color: #f07b3f;
            color: #ffffff;
            border-color: #f07b3f;
            font-weight: 600;
        }

        .page-item.dots {
            cursor: default;
        }

        .products-count {
            color: #777;
            font-size: 1rem;
            font-weight: 500;
        }

        /* ==========================================
           BARRA SUPERIOR DEL CATÁLOGO
           ========================================== */
        .catalog-top-bar {
            display: flex;
            gap: 12px;
            align-items: center;
            background: #fff;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 4px;
        }

        .catalog-main-search {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
        }

        .catalog-main-search input {
            width: 100%;
            padding: 10px 40px 10px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.3s, box-shadow 0.3s;
            background: #fafafa;
        }

        .catalog-main-search input:focus {
            border-color: #f26522;
            box-shadow: 0 0 6px rgba(242, 101, 34, 0.15);
            background: #fff;
        }

        .catalog-main-search i {
            position: absolute;
            right: 12px;
            color: #aaa;
            font-size: 1rem;
            pointer-events: none;
        }

        .catalog-sort {
            flex-shrink: 0;
        }

        .custom-sort-select {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #444;
            outline: none;
            background: #fafafa;
            cursor: pointer;
            transition: border-color 0.3s;
            min-width: 210px;
        }

        .custom-sort-select:focus {
            border-color: #f26522;
        }

        .catalog-filter-btn {
            display: none;
        }

        @media (max-width: 600px) {
            .catalog-top-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }
            .catalog-filter-btn {
                width: 100%;
                justify-content: center;
            }
            .custom-sort-select {
                min-width: 100%;
                width: 100%;
            }
            .catalog-main-search {
                max-width: 100%;
            }
        }


        @media (max-width: 992px) {
            .catalog-section {
                padding-left: 10px;
                padding-right: 10px;
            }
            .catalog-main {
                width: 100%;
                min-width: 0;
            }
            .catalog-top-bar {
                flex-wrap: wrap;
            }
            .catalog-main-search {
                max-width: 100%;
                flex: 1 1 100%;
            }
            .catalog-filter-btn {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 20px;
                padding: 10px 18px;
                font-size: 0.9rem;
                font-weight: 600;
                color: #333;
                cursor: pointer;
                white-space: nowrap;
                transition: all 0.2s;
            }
            .catalog-filter-btn:hover { background: #f5f5f5; }
            .catalog-filter-btn.active { background: #f26522; color: #fff; border-color: #f26522; }
            .catalog-section { 
                grid-template-columns: 1fr !important; 
            }
            .catalog-sidebar { 
                display: none; 
                margin-bottom: 20px; 
            }
            .catalog-sidebar.show-filters { 
                display: block; 
            }
            .catalog-sidebar #preview-filters {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 15px;
                align-items: end;
            }
            .catalog-sidebar .filter-title { grid-column: 1 / -1; margin-bottom: 5px; padding-bottom: 5px; }
            .catalog-sidebar .filter-group { margin-bottom: 0; }
        }

        @media (max-width: 576px) {
            .catalog-top-bar {
                gap: 6px;
            }
            .catalog-main-search input {
                padding: 12px 16px;
                font-size: 0.95rem;
            }
            .catalog-filter-btn {
                font-size: 0.85rem;
                padding: 8px 14px;
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 8px;
            }
            .product-card {
                padding: 8px;
            }
            .product-image-wrapper {
                height: 140px;
                margin-bottom: 12px;
            }
            .product-title {
                font-size: 0.8rem;
            }
            .product-pn {
                font-size: 0.7rem;
            }
            .product-stock {
                font-size: 0.75rem;
                margin-bottom: 12px;
            }
            .btn-details {
                font-size: 0.75rem;
                padding: 8px;
            }
        }
    </style>
@endsection
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li class="kenya-active"><a href="{{ route('catalogo') }}" class="kenya-nav-link">Cat&aacute;logo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            {{-- Sorteo temporalmente oculto en producciÃ³n --}}
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">ContÃ¡ctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="page-banner">
        <div class="banner-content">
            <h1>Catálogo Electrónico de Acuerdo Marco</h1>
        </div>
    </div>

    <main class="container catalog-section">
        <aside class="catalog-sidebar" id="filterSidebar">
            <div style="margin-bottom:12px;">
                <label for="preview-modelo" style="font-weight:700">Seleccionar modelo</label>
                <select id="preview-modelo" class="custom-select form-control">
                    <option value="">-- Todos los modelos --</option>
                    @php $selModelo = request('modelo', $id ?? null); @endphp
                    @foreach($modelos as $m)
                        <option value="{{ $m->id }}" {{ ($selModelo == $m->id) ? 'selected' : '' }}>{{ $m->descripcion }}</option>
                    @endforeach
                </select>
            </div>
            <div id="preview-filters">
                @include('partials.aside-detallemod', ['id' => $selModelo])
            </div>
        </aside>

        <div class="catalog-main">
            <!-- Barra superior: búsqueda + orden -->
            <div class="catalog-top-bar">
                <button id="toggleFiltersBtn" class="catalog-filter-btn" aria-label="Filtros">
                    <i class="fa-solid fa-sliders"></i> Filtros
                </button>
                <div class="catalog-main-search">
                    <input id="preview-search" type="text" placeholder="Buscar productos por nombre o parte..." aria-label="Buscar productos">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <div id="preview-suggestions" style="position:absolute;left:0;right:0;top:100%;z-index:40;background:#fff;border:1px solid #ddd;border-top:0;border-radius:0 0 6px 6px;display:none;max-height:320px;overflow:auto;"></div>
                </div>
                <div class="catalog-sort">
                    <select id="preview-orden" class="custom-sort-select" aria-label="Ordenar productos">
                        <option value="newest" {{ (request('orden','newest') == 'newest') ? 'selected' : '' }}>Ordenar por los últimos</option>
                        <option value="oldest" {{ (request('orden') == 'oldest') ? 'selected' : '' }}>Ordenar por los más antiguos</option>
                        <option value="nombre_asc" {{ (request('orden') == 'nombre_asc') ? 'selected' : '' }}>Nombre: A → Z</option>
                        <option value="nombre_desc" {{ (request('orden') == 'nombre_desc') ? 'selected' : '' }}>Nombre: Z → A</option>
                    </select>
                </div>
            </div>


            <div id="preview-products">
                @include('partials.catalogo-products', ['productos' => $productos])
            </div>
        </div>
    </main>

    @include('components.novedades', ['novedades' => $novedades])

@endsection

@section('js')
    <script>
        (function(){
            const modeloSelect = document.getElementById('preview-modelo');
            const filtersContainer = document.getElementById('preview-filters');
            const productsContainer = document.getElementById('preview-products');
            const filtersUrlBase = @json(url('catalogo/filters'));
            const productsUrl = @json(url('catalogo/preview-products'));
            const suggestUrl = @json(url('catalogo/preview-suggest'));
            let searchTimer = null;
            const toggleBtn = document.getElementById('toggleFiltersBtn');
            const filterSidebar = document.getElementById('filterSidebar');
            if (toggleBtn && filterSidebar) {
                toggleBtn.addEventListener('click', function () {
                    filterSidebar.classList.toggle('show-filters');
                    toggleBtn.classList.toggle('active');
                });
            }


            function executeScripts(container){
                container.querySelectorAll('script').forEach(function(oldScript){
                    var newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(function(attr){
                        newScript.setAttribute(attr.name, attr.value);
                    });
                    newScript.textContent = oldScript.textContent;
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            }

            function fetchFilters(modeloId){
                const url = modeloId ? `${filtersUrlBase}/${modeloId}` : filtersUrlBase;
                fetch(url, { credentials: 'same-origin' }).then(r => r.text()).then(html => {
                    filtersContainer.innerHTML = html;
                    executeScripts(filtersContainer);
                }).catch(err => console.error(err));
            }

            function fetchProducts(page){
                const params = new URLSearchParams(window.location.search);
                const modelo = modeloSelect.value;
                const ordenSelect = document.getElementById('preview-orden');
                const orden = ordenSelect ? ordenSelect.value : 'newest';
                if (modelo) params.set('modelo', modelo); else params.delete('modelo');
                if (orden) params.set('orden', orden); else params.delete('orden');
                if (page) params.set('page', page); else params.delete('page');
                fetch(productsUrl + '?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
                    productsContainer.innerHTML = html;
                    window.scrollTo({ top: productsContainer.offsetTop - 20, behavior: 'smooth' });
                }).catch(err => console.error(err));
            }


            // Listen for filter changes from aside-detallemod
            window.addEventListener('filterchange', function(){ fetchProducts(); });

            // Intelligent search (typeahead)
            const searchInput = document.getElementById('preview-search');
            const suggestionsBox = document.getElementById('preview-suggestions');
            let suggestTimer = null;
            let activeIndex = -1;

            function hideSuggestions(){ suggestionsBox.style.display = 'none'; suggestionsBox.innerHTML = ''; activeIndex = -1; }

            function renderSuggestions(items){
                if (!items || items.length === 0) { hideSuggestions(); return; }
                suggestionsBox.innerHTML = items.map((it, idx) => `\
                    <div class="preview-suggestion-item" data-index="${idx}" data-url="${it.url}" style="display:flex;gap:8px;padding:8px;align-items:center;cursor:pointer;border-bottom:1px solid #f1f1f1;">\
                        <img src="${it.img}" width="48" height="48" style="object-fit:cover;border-radius:4px;">\
                        <div style="flex:1;">\
                            <div style="font-weight:700;color:#222;">${it.nombre}</div>\
                            <div style="font-size:12px;color:#666;margin-top:3px;">${it.modelo} &middot; <small>${it.nro_parte}</small></div>\
                        </div>\
                    </div>`).join('');

                suggestionsBox.style.display = 'block';
            }

            function fetchSuggest(q){
                const modelo = modeloSelect ? modeloSelect.value : '';
                const params = new URLSearchParams();
                params.set('q', q);
                if (modelo) params.set('modelo', modelo);
                fetch(suggestUrl + '?' + params.toString(), { credentials: 'same-origin' })
                    .then(r => r.json())
                    .then(data => renderSuggestions(data))
                    .catch(err => { console.error(err); hideSuggestions(); });
            }

            function applySearchQuery(q){
                const params = new URLSearchParams(window.location.search);
                if (q) params.set('busqueda', q); else params.delete('busqueda');
                params.delete('page');
                history.replaceState({}, '', window.location.pathname + '?' + params.toString());
                hideSuggestions();
                fetchProducts();
            }

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const q = (e.target.value || '').trim();
                    clearTimeout(suggestTimer);
                    clearTimeout(searchTimer);
                    if (q.length === 0) { hideSuggestions(); applySearchQuery(''); return; }
                    suggestTimer = setTimeout(() => fetchSuggest(q), 250);
                    searchTimer = setTimeout(() => applySearchQuery(q), 500);
                });

                // keyboard navigation
                searchInput.addEventListener('keydown', (e) => {
                    const items = suggestionsBox.querySelectorAll('.preview-suggestion-item');
                    if (!items.length) return;
                    if (e.key === 'ArrowDown') { e.preventDefault(); activeIndex = Math.min(activeIndex + 1, items.length - 1); items.forEach((it,i)=>it.style.background=i===activeIndex? '#f5f5f5':'' ); }
                    else if (e.key === 'ArrowUp') { e.preventDefault(); activeIndex = Math.max(activeIndex - 1, 0); items.forEach((it,i)=>it.style.background=i===activeIndex? '#f5f5f5':'' ); }
                    else if (e.key === 'Enter') {
                        e.preventDefault();
                        if (activeIndex >= 0 && items[activeIndex]) {
                            window.location.href = items[activeIndex].dataset.url;
                        } else {
                            const q = (e.target.value || '').trim();
                            applySearchQuery(q);
                        }
                    }
                });

                // click on suggestion
                suggestionsBox.addEventListener('click', (e) => {
                    const item = e.target.closest('.preview-suggestion-item');
                    if (item) window.location.href = item.dataset.url;
                });
            }

            if (modeloSelect){
                modeloSelect.addEventListener('change', () => {
                    const params = new URLSearchParams(window.location.search);
                    if (modeloSelect.value) {
                        params.set('modelo', modeloSelect.value);
                        fetchFilters(modeloSelect.value);
                    } else {
                        params.delete('modelo');
                        filtersContainer.innerHTML =
                            '<p style="padding:15px;color:#666;font-size:14px;">Seleccione un modelo para ver los filtros disponibles.</p>';
                    }
                    params.delete('page');
                    history.replaceState({}, '', window.location.pathname + '?' + params.toString());
                    fetchProducts();
                });
            }

            // Sort order change
            const ordenSelect = document.getElementById('preview-orden');
            if (ordenSelect) {
                ordenSelect.addEventListener('change', () => {
                    const params = new URLSearchParams(window.location.search);
                    params.set('orden', ordenSelect.value);
                    params.delete('page');
                    history.replaceState({}, '', window.location.pathname + '?' + params.toString());
                    fetchProducts();
                });
            }

            // AJAX pagination via event delegation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a.page-item');
                if (!link) return;
                if (!productsContainer.contains(link)) return;
                e.preventDefault();
                const href = link.getAttribute('href');
                if (!href) return;
                const urlObj = new URL(href, window.location.origin);
                const page = urlObj.searchParams.get('page') || '1';
                const params = new URLSearchParams(window.location.search);
                params.set('page', page);
                history.replaceState({}, '', window.location.pathname + '?' + params.toString());
                fetchProducts(page);
            });
        })();
    </script>
    <script src="{{ asset('js/detallemod.js') }}"></script>
@endsection

