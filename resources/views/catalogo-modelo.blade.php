@extends('layouts.landing')

@section('title', $modelo->descripcion ?? 'Catálogo')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detallemod.css') }}">
    <style>
        :root {
            --primary-color: #ee7c31;
            --secondary-color: #ca7b46;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --border-radius: 8px;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        .catalog-section {
            padding: 2rem 0;
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            min-height: 100vh;
            margin-top: 4rem;
        }
        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .catalog-filters {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }
        .search-box { position: relative; display: flex; }
        .search-input {
            width: 100%; padding: 0.75rem 1rem; border: 2px solid #ddd;
            border-radius: var(--border-radius); font-size: 1rem;
            transition: var(--transition);
        }
        .search-input:focus {
            border-color: var(--primary-color); outline: none;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        .search-btn {
            position: absolute; right: 0; top: 0; height: 100%; width: 50px;
            background: var(--primary-color); color: white; border: none;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer; transition: var(--transition);
        }
        .search-btn:hover { background: var(--secondary-color); }
        .filter-controls { display: flex; gap: 1rem; }
        .category-filter, .sort-filter {
            flex: 1; padding: 0.75rem; border: 2px solid #ddd;
            border-radius: var(--border-radius); font-size: 1rem;
            background-color: white; cursor: pointer; transition: var(--transition);
        }
        .category-filter:focus, .sort-filter:focus {
            border-color: var(--primary-color); outline: none;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        .product-grid { margin-top: 0.5rem; }
        .product-card {
            background: white; border-radius: var(--border-radius);
            overflow: hidden; box-shadow: var(--box-shadow);
            transition: var(--transition); height: 100%;
            display: flex; flex-direction: column;
            border: 1px solid #f0f0f0;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
            border-color: #ffd5b8;
        }
        .product-badge {
            position: absolute; top: 10px; right: 10px;
            padding: 0.25rem 0.75rem; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600; color: white; z-index: 2;
        }
        .product-badge.out-of-stock { background-color: var(--accent-color); }
        .product-badge { background-color: var(--success-color); }
        .product-image {
            position: relative; overflow: hidden; padding-top: 75%;
            background: #fafafa;
        }
        .product-image img {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; transition: var(--transition);
        }
        .product-card:hover .product-image img { transform: scale(1.05); }
        .product-actions {
            position: absolute; bottom: 10px; right: 10px;
            display: flex; gap: 0.5rem; z-index: 2;
        }
        .quick-view {
            width: 36px; height: 36px; border-radius: 50%;
            background-color: rgba(255,255,255,0.9); border: none;
            color: var(--dark-color); display: flex; align-items: center;
            justify-content: center; cursor: pointer;
            transition: var(--transition); box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .quick-view:hover {
            background-color: white; color: var(--primary-color); transform: scale(1.1);
        }
        .product-info {
            padding: 1.25rem 1.5rem 1.5rem; flex-grow: 1; display: flex; flex-direction: column;
        }
        .product-title {
            font-size: 1.05rem; margin-bottom: 0.85rem; color: var(--dark-color);
            font-weight: 700; line-height: 1.35; min-height: 2.7em;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .product-details { margin-bottom: 1rem; }
        .detail-row {
            display: flex; justify-content: space-between; align-items: center;
            margin: 0 0 0.45rem 0; font-size: 0.88rem; color: #555;
            padding: 4px 0; border-bottom: 1px solid #f5f5f5;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            color: #888; font-weight: 500; font-size: 0.82rem;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .detail-value {
            color: #1a1a1a; font-weight: 600; font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }
        .in-stock {
            color: var(--success-color); font-weight: 700; font-size: 0.85rem;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .in-stock::before {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--success-color);
        }
        .out-of-stock { color: var(--accent-color); font-weight: 600; font-size: 0.85rem; }
        .view-details {
            margin-top: auto; width: 100%; padding: 0.7rem;
            background-color: var(--primary-color); color: white; border: none;
            border-radius: var(--border-radius); font-weight: 600;
            cursor: pointer; transition: var(--transition);
            text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.85rem;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        }
        .view-details:hover { background-color: var(--secondary-color); transform: translateY(-1px); }
        .view-details::after { content: '→'; font-size: 1.1rem; }
        .catalog-pagination { margin-top: 3rem; }
        .pagination { display: flex; gap: 0.5rem; }
        .page-item.disabled .page-link { opacity: 0.5; pointer-events: none; }
        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color); color: white;
        }
        .page-link {
            padding: 0.5rem 1rem; border: 1px solid #ddd;
            border-radius: var(--border-radius); color: var(--dark-color);
            transition: var(--transition);
        }
        .page-link:hover { background-color: #f8f9fa; border-color: #ddd; }
        .alert { padding: 1rem; border-radius: var(--border-radius); text-align: center; }
        .alert-warning {
            background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;
        }
        @media (max-width: 768px) {
            .filter-controls { flex-direction: column; gap: 0.75rem; }
            .product-title { font-size: 1.1rem; }
        }
        @media (max-width: 992px) {
            .col-lg-3 { display: none; }
            .col-lg-9 { flex: 0 0 100%; max-width: 100%; }
        }
        @media (max-width: 576px) {
            .catalog-filters .row > div { margin-bottom: 1rem; }
            .product-card { max-width: 320px; margin-left: auto; margin-right: auto; }
        }
    </style>
@endsection
@section('menu')
    <nav class="kenya-main-nav kenya-float-right kenya-d-none kenya-d-lg-block">
        <ul class="kenya-nav-list">
            <li><a href="{{ url('/') }}" class="kenya-nav-link"><i class="bx bx-home kenya-nav-icon"></i> Inicio</a></li>
            <li><a href="{{ route('quienes.somos') }}" class="kenya-nav-link">Quienes Somos</a></li>
            <li class="kenya-active"><a href="{{ route('catalogo') }}" class="kenya-nav-link">Catálogo</a></li>
            <li><a href="{{ route('novedades') }}" class="kenya-nav-link">Novedades</a></li>
            <li><a href="{{ route('consultar.garantia') }}" class="kenya-nav-link">Soporte</a></li>
            <li><a href="{{ route('contactenos') }}" class="kenya-nav-link">Contáctenos</a></li>
        </ul>
    </nav>
@endsection

@section('content')
    <section class="catalog-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div style="margin-bottom:12px;">
                        <label for="preview-modelo" style="font-weight:700">Seleccionar modelo</label>
                        <select id="preview-modelo" class="form-control">
                            <option value="">-- Todos los modelos --</option>
                            @foreach($modelos as $m)
                                <option value="{{ $m->id }}" {{ ($id == $m->id) ? 'selected' : '' }}>{{ $m->descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="preview-filters">
                        @include('partials.aside-detallemod', ['id' => $id])
                    </div>
                </div>
                <div class="col-lg-9">
                    <div style="margin-bottom:12px;">
                        <div style="position:relative; max-width:600px;">
                            <input id="preview-search" type="text" placeholder="Buscar productos por nombre o parte..." class="search-input form-control" aria-label="Buscar productos">
                            <div id="preview-suggestions" style="position:absolute;left:0;right:0;z-index:40;background:#fff;border:1px solid #ddd;border-top:0;display:none;max-height:320px;overflow:auto;"></div>
                        </div>
                    </div>
                    <div id="preview-products">
                        @include('partials.catalogo-products', ['productos' => $productos])
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                if (modelo) params.set('modelo', modelo); else params.delete('modelo');
                if (page) params.set('page', page);
                fetch(productsUrl + '?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
                    productsContainer.innerHTML = html;
                    window.scrollTo({ top: productsContainer.offsetTop - 20, behavior: 'smooth' });
                }).catch(err => console.error(err));
            }

            // Listen for filter changes from aside-detallemod
            window.addEventListener('filterchange', function(){ fetchProducts(); });

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
                            <div style="font-size:12px;color:#666;margin-top:3px;">${it.modelo} · <small>${it.nro_parte}</small></div>\
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

                suggestionsBox.addEventListener('click', (e) => {
                    const item = e.target.closest('.preview-suggestion-item');
                    if (item) window.location.href = item.dataset.url;
                });
            }

            if (modeloSelect){
                modeloSelect.addEventListener('change', () => {
                    const newId = modeloSelect.value;
                    if (newId) {
                        fetchFilters(newId);
                        window.location.href = @json(url('catalogo/')) + '/' + newId + '/detallemod';
                    } else {
                        filtersContainer.innerHTML =
                            '<p style="padding:15px;color:#666;font-size:14px;">Seleccione un modelo para ver los filtros disponibles.</p>';
                        fetchProducts();
                    }
                });
            }

            // AJAX pagination via event delegation
            document.addEventListener('click', function(e) {
                const link = e.target.closest('.page-link');
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
