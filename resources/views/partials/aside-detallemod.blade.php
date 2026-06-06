@php
    use App\Producto;
    use App\Modelo;

    $modId = $id ?? (request()->route('id') ?? request()->route('modelo'));

    $isTonerModel = isset($isTonerModel) ? (bool) $isTonerModel : false;
    if (!$isTonerModel && $modId) {
        $modeloTmp = Modelo::find($modId);
        $modeloDesc = mb_strtolower((string) ($modeloTmp->descripcion ?? ''));
        $isTonerModel = ((int) ($modeloTmp->id ?? 0) === 10)
            || str_contains($modeloDesc, 'toner')
            || str_contains($modeloDesc, 'tonner');
    }

    $specs = [];

    if ($isTonerModel) {
        $tonerSpecMap = [
            'tipo_suministro'       => 'Tipo de suministro',
            'modelo_toner'          => 'Modelo',
            'color_toner'           => 'Color',
            'rendimiento_toner'     => 'Rendimiento',
            'garantia_toner'        => 'Garantía de Fábrica',
            'sistema_raee'          => 'Sistema RAEE',
            'certificaciones_toner' => 'Certificaciones',
            'empaque_toner'         => 'Empaque',
            'unidad_toner'          => 'Unidad',
            'dimensiones_toner'     => 'Dimensiones',
        ];

        foreach ($tonerSpecMap as $paramKey => $campo) {
            $values = \DB::table('especificaciones')
                ->join('productos', 'especificaciones.producto_id', '=', 'productos.id')
                ->where('productos.modelo_id', $modId)
                ->where('productos.pagina_web', 'SI')
                ->where(function ($q) {
                    $q->whereNull('productos.vigencia')
                      ->orWhereNotIn('productos.vigencia', ['SUSPENDIDA', 'INACTIVA', 'ANULADA']);
                })
                ->where('especificaciones.campo', $campo)
                ->whereRaw("TRIM(especificaciones.descripcion) NOT IN ('', 'null', 'NULL', 'none', 'NONE', 'N/A', 'n/a', '-', 'NO APLICA', 'N.A.')")
                ->distinct()
                ->orderBy('especificaciones.descripcion')
                ->pluck('especificaciones.descripcion')
                ->map(fn($v) => trim((string) $v))
                ->filter(fn($v) => $v !== '')
                ->unique()
                ->values();

            if ($values->isNotEmpty()) {
                $specs[$paramKey] = ['label' => $campo, 'options' => $values];
            }
        }

        $numeroParte = Producto::query()
            ->where('modelo_id', $modId)
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->whereNotNull('nro_parte')
            ->whereRaw("TRIM(nro_parte) != ''")
            ->distinct()
            ->orderBy('nro_parte')
            ->pluck('nro_parte')
            ->map(fn($v) => trim((string) $v))
            ->filter(fn($v) => $v !== '')
            ->unique()
            ->values();

        if ($numeroParte->isNotEmpty()) {
            $specs['numero_parte_toner'] = ['label' => 'Número de parte', 'options' => $numeroParte];
        }
    } else {
        $specFields = [
            'procesador'       => 'Procesador',
            'ram'              => 'Memoria RAM',
            'almacenamiento'   => 'Almacenamiento',
            'tarjetavideo'     => 'Tarjeta de Video',
            'sistema_operativo'=> 'Sistema Operativo',
            'unidad_optica'    => 'Unidad Óptica',
            'conectividad'     => 'Conectividad LAN',
            'conectividad_wlan'=> 'Conectividad WLAN',
            'conectividad_usb' => 'Conectividad USB',
            'video_vga'        => 'Salida VGA',
            'video_hdmi'       => 'Salida HDMI',
            'suite_ofimatica'  => 'Ofimática',
            'teclado'          => 'Teclado',
            'mouse'            => 'Mouse',
        ];

        foreach ($specFields as $col => $label) {
            $values = Producto::select($col)
                ->where('modelo_id', $modId)
                ->where('pagina_web', 'SI')
                ->noSuspendido()
                ->distinct()
                ->whereNotNull($col)
                ->whereRaw("TRIM($col) NOT IN ('', 'null', 'NULL', 'none', 'NONE', 'N/A', 'n/a', 'NO APLICA', 'N.A.')")
                ->orderBy($col)
                ->pluck($col)
                ->map(fn($v) => trim($v))
                ->filter(fn($v) => $v !== '')
                ->unique()
                ->values();

            if ($values->isNotEmpty()) {
                $specs[$col] = ['label' => $label, 'options' => $values];
            }
        }

        // Filtros para monitores: specs almacenadas en tabla especificaciones
        $monitorSpecMap = [
            'espec_tamano'      => 'Tamaño de Pantalla',
            'espec_panel'       => 'Panel',
            'espec_hdmi'        => 'HDMI',
            'espec_displayport' => 'DisplayPort',
            'espec_garantia'    => 'Garantía de Fábrica',
        ];
        foreach ($monitorSpecMap as $paramKey => $campo) {
            $values = \DB::table('especificaciones')
                ->join('productos', 'especificaciones.producto_id', '=', 'productos.id')
                ->where('productos.modelo_id', $modId)
                ->where('productos.pagina_web', 'SI')
                ->where(function ($q) {
                    $q->whereNull('productos.vigencia')
                      ->orWhereNotIn('productos.vigencia', ['SUSPENDIDA', 'INACTIVA', 'ANULADA']);
                })
                ->where('especificaciones.campo', $campo)
                ->whereRaw("LOWER(TRIM(especificaciones.descripcion)) != 'no'")
                ->whereRaw("TRIM(especificaciones.descripcion) != ''")
                ->distinct()
                ->orderBy('especificaciones.descripcion')
                ->pluck('especificaciones.descripcion')
                ->filter(fn($v) => trim($v) !== '')
                ->values();
            if ($values->isNotEmpty()) {
                $specs[$paramKey] = ['label' => $campo, 'options' => $values];
            }
        }
    }
@endphp

<div class="aside-catalogo-container">
    <h4 class="aside-title-custom">Filtros por Especificaciones</h4>

    <aside>
        @foreach($specs as $campo => $spec)
            @php
                $activeValues = request($campo) ? explode(',', request($campo)) : [];
                $isOpen = !empty($activeValues);
            @endphp
            <div class="seccion_filtro">
                <div class="boton_filtros" style="font-size: 13.5px;" onclick="toggleFilter('{{ $campo }}')">
                    <button type="button" style="text-align: left; background: none; border: none; width: 100%; font-weight: 600; font-size: 13.5px; color: #000; padding: 0; outline: none; cursor: pointer;">
                        {{ $spec['label'] }}
                    </button>
                    <div class="icon_boton_filtros">
                        <i id="icon_{{ $campo }}" class="fa-solid {{ $isOpen ? 'fa-minus' : 'fa-plus' }}"></i>
                    </div>
                </div>

                <div class="filter-search-wrap" id="search_wrap_{{ $campo }}" style="display: {{ $isOpen ? 'block' : 'none' }};">
                    <input type="text" class="filter-search-input" data-campo="{{ $campo }}" placeholder="Buscar {{ $spec['label'] }}..." style="width:100%; padding:4px 8px; font-size:12px; border:1px solid #ddd; border-radius:4px; outline:none; box-sizing:border-box;">
                </div>

                <ul id="list_{{ $campo }}" class="lista" style="{{ $isOpen ? 'display: block;' : 'display: none;' }}">
                    @foreach($spec['options'] as $opt)
                        @php
                            $isChecked = in_array($opt, $activeValues);
                        @endphp
                        <li class="item_producto" style="font-weight: bold; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox"
                                   id="chk_{{ $campo }}_{{ $loop->index }}"
                                   value="{{ $opt }}"
                                   {{ $isChecked ? 'checked' : '' }}
                                   onchange="submitFilter('{{ $campo }}', '{{ $opt }}', this.checked)"
                                   style="cursor: pointer; width: 16px; height: 16px; margin: 0;">
                            <label for="chk_{{ $campo }}_{{ $loop->index }}" style="font-size: 14px; margin-bottom: 0; cursor: pointer; font-weight: bold; color: #444;">
                                {{ $opt }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach

        <!-- Limpiar Filtros -->
        <div style="margin-top: 15px; padding: 0 10px;">
            <a href="{{ url('catalogo/' . $modId . '/detallemod') }}" class="btn btn-secondary btn-sm w-100" style="background-color: #6c757d; border-color: #6c757d; color: white; display: block; text-align: center; padding: 8px; border-radius: 4px; font-weight: bold; text-decoration: none;">
                <i class="fas fa-times-circle"></i> Limpiar Filtros
            </a>
        </div>
    </aside>
</div>

<script>
    function toggleFilter(campo) {
        const list = document.getElementById('list_' + campo);
        const icon = document.getElementById('icon_' + campo);
        const searchWrap = document.getElementById('search_wrap_' + campo);
        if (list.style.display === 'none') {
            list.style.display = 'block';
            icon.classList.remove('fa-plus');
            icon.classList.add('fa-minus');
            if (searchWrap) searchWrap.style.display = 'block';
        } else {
            list.style.display = 'none';
            icon.classList.remove('fa-minus');
            icon.classList.add('fa-plus');
            if (searchWrap) { searchWrap.style.display = 'none'; }
        }
    }

    document.querySelectorAll('.filter-search-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const campo = this.dataset.campo;
            const text = this.value.toLowerCase().trim();
            document.querySelectorAll('#list_' + campo + ' .item_producto').forEach(function(li) {
                const label = li.querySelector('label');
                li.style.display = (!text || (label && label.textContent.toLowerCase().includes(text))) ? '' : 'none';
            });
        });
    });

    function submitFilter(campo, valor, isChecked) {
        const params = new URLSearchParams(window.location.search);
        let activeValues = params.get(campo) ? params.get(campo).split(',') : [];

        if (isChecked) {
            if (!activeValues.includes(valor)) {
                activeValues.push(valor);
            }
        } else {
            activeValues = activeValues.filter(v => v !== valor);
        }

        if (activeValues.length > 0) {
            params.set(campo, activeValues.join(','));
        } else {
            params.delete(campo);
        }

        // Quitar el parámetro 'page' para volver a la página 1 al filtrar
        params.delete('page');

        window.location.href = window.location.pathname + '?' + params.toString();
    }
</script>

<style>
.aside-catalogo-container {
    background: #ffffff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.aside-title-custom {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #333;
    padding-bottom: 5px;
    border-bottom: 2px solid #ee7c31;
}
.seccion_filtro {
    margin-bottom: 8px;
}
.seccion_filtro .lista {
    position: relative !important;
    max-height: 220px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 6px 6px;
    box-shadow: none !important;
    z-index: 10;
    margin-top: 0;
    list-style: none;
    padding: 5px 10px !important;
}
.item_producto {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-bottom: 1px solid #f1f5f9;
}
.item_producto:last-child {
    border-bottom: none;
}
.filter-search-wrap {
    padding: 4px 8px;
}
.filter-search-input:focus {
    border-color: #ee7c31 !important;
    box-shadow: 0 0 0 2px rgba(238,124,49,0.15);
}
</style>
