@php
    use App\Producto;
    use App\Modelo;

    $modId = $id ?? (request()->route('id') ?? (request('modelo') ?? request()->route('modelo')));

    $isTonerModel = isset($isTonerModel) ? (bool) $isTonerModel : false;
    $isMonitorModel = false;
    if ($modId) {
        $modeloTmp = Modelo::find($modId);
        $modeloDesc = mb_strtolower((string) ($modeloTmp->descripcion ?? ''));
        $isTonerModel = ((int) ($modeloTmp->id ?? 0) === 10)
            || str_contains($modeloDesc, 'toner')
            || str_contains($modeloDesc, 'tonner');
        $isMonitorModel = ((int) ($modeloTmp->id ?? 0) === 16)
            || str_contains($modeloDesc, 'raito')
            || str_contains($modeloDesc, 'monitor');
    }

    $specs = [];

    /**
     * Normalizadores canónicos compartidos
     */
    $normalizarTV = function(string $v): string {
        $v = trim($v);
        $v = preg_replace('/\b(dedicad[oa]s?|integrad[oa]s?)\b/i', '', $v);
        $v = trim($v);
        $v = preg_replace('/\s+/', ' ', $v);
        $v = trim($v);
        $v = preg_replace('/\s*-\s*/', '-', $v);
        $v = trim($v);
        $v = preg_replace('/\bConectividadº?\b/i', '', $v);
        $v = trim($v);

        if (preg_match('/^(.*?\d+\s*GB(?:\s*G?DDR\d[X]?)?)/i', $v, $m)) {
            $v = trim($m[1]);
        } else {
            $v = preg_replace(
                '/\s+(OC|GAMING|EDITION|PLUS|SUPER|BOOST|EX|AERO|EAGLE|VISION|'
                . 'WINDFORCE|PULSE|MECH|TWIN|TUF|ROG|STRIX|NITRO|PHANTOM|'
                . 'REBEL|TRIPLE|DUAL|FAN|GDDR\d+|DDR\d+|V\d+|VR|READY)\b.*/i',
                '',
                $v
            );
        }
        $v = preg_replace('/(\d+)\s*GB/i', '$1 GB', $v);
        $v = preg_replace('/GB\s*(G?DDR\d[X]?)/i', 'GB $1', $v);
        $v = ltrim($v, '- ');
        return trim($v);
    };

    $normalizarCPU = function(string $v): string {
        $v = trim($v);
        $v = preg_replace('/\s+\d+(\.\d+)?\s*GHZ$/i', '', $v);
        return trim($v);
    };

    $normalizarRAM = function(string $v): string {
        $v = trim($v);
        if (preg_match('/^(\d+\s*GB\s+DDR\d\s+\d{3,4})/i', $v, $m)) {
            return trim(strtoupper($m[1])) . ' MHz';
        }
        $v = preg_replace('/\s+/', ' ', $v);
        return strtoupper(trim($v));
    };

    $normalizarAlmacenamiento = function(string $v): string {
        $v = trim($v);
        if ($v === '') return '';
        $v = preg_replace('/\s+/', ' ', $v);

        if (str_contains($v, '/')) {
            $partes = explode('/', $v);
            $partesNorm = array_map(function($p) {
                $p = trim($p);
                $p = preg_replace('/(\d+)\s*(TB|GB)/i', '$1 $2', $p);
                $p = preg_replace_callback('/\b(ssd|hdd|nvme|rpm|m\.2)\b/i', fn($m) => strtoupper($m[1]), $p);
                $p = preg_replace('/\s+/', ' ', $p);
                return trim($p);
            }, $partes);

            return implode(' / ', $partesNorm);
        }

        $v = preg_replace('/(\d+)\s*(TB|GB)/i', '$1 $2', $v);

        if (preg_match('/^(\d+)\s*[xX]\s*(\d+)\s*(TB|GB)/i', $v, $m)) {
            $qty = (int)$m[1];
            $size = (int)$m[2];
            $unit = strtoupper($m[3]);
            $total = $qty * $size;
            $capacidad = "$total $unit";
        } elseif (preg_match('/(\d+(?:\.\d+)?)\s*(TB|GB)/i', $v, $m)) {
            $capacidad = $m[1] . ' ' . strtoupper($m[2]);
        } else {
            return $v;
        }

        if (preg_match('/\b(SSD|M\.2|NVME)\b/i', $v)) {
            return "$capacidad (SSD/ M.2/ NVME)";
        }

        if (preg_match('/\bHDD\b/i', $v)) {
            return "$capacidad HDD";
        }

        return $v;
    };

    $normalizarGarantia = function(string $v): ?string {
        $v = strtoupper(trim($v));
        if (!preg_match('/(\d+)\s*MESES/i', $v, $m)) return null;
        $meses = $m[1] . ' MESES';
        if (str_contains($v, 'ON-SITE') || str_contains($v, 'ON SITE')) $tipo = 'ON-SITE';
        elseif (str_contains($v, 'CARRY-IN') || str_contains($v, 'CARRY IN')) $tipo = 'CARRY-IN';
        else $tipo = 'ESTÁNDAR';
        return "$meses $tipo";
    };

    // ─────────────────────────────────────────────────────────────────────────────
    // CASO 1: MONITORES (RAITO)
    // ─────────────────────────────────────────────────────────────────────────────
    if ($isMonitorModel) {
        $monitorSpecMap = [
            'espec_tamano'      => 'Tamaño de Pantalla',
            'espec_panel'       => 'Panel',
            'espec_hdmi'        => 'HDMI',
            'espec_displayport' => 'DisplayPort',
            'espec_garantia'    => 'Garantía de Fábrica',
        ];

        $monitorQuery = Producto::query()
            ->where('modelo_id', $modId)
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->with('especificaciones');

        if (request()->filled('busqueda')) {
            $monitorQuery->intelligentSearch(request('busqueda'));
        }

        $monitorProducts = $monitorQuery->get();

        $preparedMonitors = [];
        foreach ($monitorProducts as $p) {
            $row = [];
            foreach ($p->especificaciones as $esp) {
                $campo = trim($esp->campo);
                $desc = trim($esp->descripcion);
                if ($desc === '' || strtolower($desc) === 'no' || in_array(strtoupper($desc), ['NULL', 'NONE', 'N/A', 'NO APLICA', 'N.A.'], true)) {
                    continue;
                }
                if ($campo === 'Garantía de Fábrica') {
                    $normG = $normalizarGarantia($desc);
                    if ($normG) $row['espec_garantia'] = $normG;
                } else {
                    foreach ($monitorSpecMap as $paramKey => $cName) {
                        if ($cName === $campo) {
                            $row[$paramKey] = $desc;
                        }
                    }
                }
            }
            $preparedMonitors[] = $row;
        }

        $activeMonitorFilters = [];
        foreach ($monitorSpecMap as $paramKey => $campo) {
            if (request()->filled($paramKey)) {
                $vals = array_filter(array_map('trim', explode(',', (string)request($paramKey))), fn($v) => $v !== '');
                if (!empty($vals)) {
                    $activeMonitorFilters[$paramKey] = $vals;
                }
            }
        }

        foreach ($monitorSpecMap as $paramKey => $campo) {
            $counts = [];
            foreach ($preparedMonitors as $p) {
                $matchesOthers = true;
                foreach ($activeMonitorFilters as $otherKey => $allowedValues) {
                    if ($otherKey === $paramKey) continue;
                    $pVal = $p[$otherKey] ?? null;
                    if ($pVal === null || !in_array($pVal, $allowedValues, true)) {
                        $matchesOthers = false;
                        break;
                    }
                }
                if ($matchesOthers) {
                    $val = $p[$paramKey] ?? null;
                    if ($val !== null) {
                        $counts[$val] = ($counts[$val] ?? 0) + 1;
                    }
                }
            }

            $activeForParam = $activeMonitorFilters[$paramKey] ?? [];
            $options = [];
            foreach ($counts as $val => $cnt) {
                $options[] = [
                    'value' => $val,
                    'count' => $cnt,
                ];
            }
            $existingValues = array_column($options, 'value');
            foreach ($activeForParam as $checkedVal) {
                if (!in_array($checkedVal, $existingValues, true)) {
                    $options[] = [
                        'value' => $checkedVal,
                        'count' => 0,
                    ];
                }
            }

            if (!empty($options)) {
                usort($options, fn($a, $b) => strnatcasecmp($a['value'], $b['value']));
                $specs[$paramKey] = [
                    'label' => $campo,
                    'options' => $options,
                ];
            }
        }
    }
    // ─────────────────────────────────────────────────────────────────────────────
    // CASO 2: TÓNER (HENKO)
    // ─────────────────────────────────────────────────────────────────────────────
    elseif ($isTonerModel) {
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

        $tonerQuery = Producto::query()
            ->where('modelo_id', $modId)
            ->where('pagina_web', 'SI')
            ->noSuspendido()
            ->with('especificaciones');

        if (request()->filled('busqueda')) {
            $tonerQuery->intelligentSearch(request('busqueda'));
        }

        $tonerProducts = $tonerQuery->get();

        $preparedToner = [];
        foreach ($tonerProducts as $p) {
            $row = [];
            if (!empty($p->nro_parte)) {
                $row['numero_parte_toner'] = trim((string)$p->nro_parte);
            }
            foreach ($p->especificaciones as $esp) {
                $campo = trim($esp->campo);
                $desc = trim($esp->descripcion);
                if ($desc === '' || in_array(strtoupper($desc), ['NULL', 'NONE', 'N/A', '-', 'NO APLICA', 'N.A.'], true)) {
                    continue;
                }
                if ($campo === 'Garantía de Fábrica') {
                    $normG = $normalizarGarantia($desc);
                    if ($normG) $row['garantia_toner'] = $normG;
                } else {
                    foreach ($tonerSpecMap as $paramKey => $cName) {
                        if ($cName === $campo) {
                            $row[$paramKey] = $desc;
                        }
                    }
                }
            }
            $preparedToner[] = $row;
        }

        $allTonerFields = $tonerSpecMap;
        $allTonerFields['numero_parte_toner'] = 'Número de parte';

        $activeTonerFilters = [];
        foreach ($allTonerFields as $paramKey => $campo) {
            if (request()->filled($paramKey)) {
                $vals = array_filter(array_map('trim', explode(',', (string)request($paramKey))), fn($v) => $v !== '');
                if (!empty($vals)) {
                    $activeTonerFilters[$paramKey] = $vals;
                }
            }
        }

        foreach ($allTonerFields as $paramKey => $campo) {
            $counts = [];
            foreach ($preparedToner as $p) {
                $matchesOthers = true;
                foreach ($activeTonerFilters as $otherKey => $allowedValues) {
                    if ($otherKey === $paramKey) continue;
                    $pVal = $p[$otherKey] ?? null;
                    if ($pVal === null || !in_array($pVal, $allowedValues, true)) {
                        $matchesOthers = false;
                        break;
                    }
                }
                if ($matchesOthers) {
                    $val = $p[$paramKey] ?? null;
                    if ($val !== null) {
                        $counts[$val] = ($counts[$val] ?? 0) + 1;
                    }
                }
            }

            $activeForParam = $activeTonerFilters[$paramKey] ?? [];
            $options = [];
            foreach ($counts as $val => $cnt) {
                $options[] = [
                    'value' => $val,
                    'count' => $cnt,
                ];
            }
            $existingValues = array_column($options, 'value');
            foreach ($activeForParam as $checkedVal) {
                if (!in_array($checkedVal, $existingValues, true)) {
                    $options[] = [
                        'value' => $checkedVal,
                        'count' => 0,
                    ];
                }
            }

            if (!empty($options)) {
                usort($options, fn($a, $b) => strnatcasecmp($a['value'], $b['value']));
                $specs[$paramKey] = [
                    'label' => $campo,
                    'options' => $options,
                ];
            }
        }
    }
    // ─────────────────────────────────────────────────────────────────────────────
    // CASO 3: PCs DE ESCRITORIO / WORKSTATIONS (EZENT, GENWORK, OFISZU, PROWORK)
    // ─────────────────────────────────────────────────────────────────────────────
    else {
        $specFields = [
            'procesador'       => 'Procesador',
            'ram'              => 'Memoria RAM',
            'almacenamiento'   => 'Almacenamiento',
            'tarjetavideo'     => 'Tarjeta de Video',
            'sistema_operativo'=> 'Sistema Operativo',
            'unidad_optica'    => 'Unidad Óptica',
            'conectividad_wlan'=> 'Conectividad WLAN',
            'video_vga'        => 'Salida VGA',
            'video_hdmi'       => 'Salida HDMI',
            'suite_ofimatica'  => 'Ofimática',
        ];

        $prodQuery = Producto::query()
            ->where('pagina_web', 'SI')
            ->noSuspendido();

        if ($modId) {
            $prodQuery->where('modelo_id', $modId);
        }
        if (request()->filled('busqueda')) {
            $prodQuery->intelligentSearch(request('busqueda'));
        }

        $allProducts = $prodQuery->select(array_merge(['id', 'modelo_id'], array_keys($specFields)))->get();

        $preparedProducts = [];
        foreach ($allProducts as $p) {
            $row = [];
            foreach ($specFields as $col => $lbl) {
                $raw = (string)($p->$col ?? '');
                $rawTrim = trim($raw);
                if ($rawTrim === '' || in_array(strtoupper($rawTrim), ['', 'NULL', 'NONE', 'N/A', 'NO APLICA', 'N.A.'], true)) {
                    $row[$col] = null;
                    continue;
                }
                if ($col === 'tarjetavideo') $norm = $normalizarTV($rawTrim);
                elseif ($col === 'procesador') $norm = $normalizarCPU($rawTrim);
                elseif ($col === 'ram') $norm = $normalizarRAM($rawTrim);
                elseif ($col === 'almacenamiento') $norm = $normalizarAlmacenamiento($rawTrim);
                else $norm = $rawTrim;

                $row[$col] = ($norm !== '') ? $norm : null;
            }
            $preparedProducts[] = $row;
        }

        $activeFilters = [];
        foreach ($specFields as $col => $lbl) {
            if (request()->filled($col)) {
                $vals = array_filter(array_map('trim', explode(',', (string)request($col))), fn($v) => $v !== '');
                if (!empty($vals)) {
                    $activeFilters[$col] = $vals;
                }
            }
        }

        foreach ($specFields as $col => $label) {
            $counts = [];
            foreach ($preparedProducts as $p) {
                $matchesOthers = true;
                foreach ($activeFilters as $otherCol => $allowedValues) {
                    if ($otherCol === $col) continue; // Multi-select facet rule: excluir campo actual
                    $pVal = $p[$otherCol] ?? null;
                    if ($pVal === null || !in_array($pVal, $allowedValues, true)) {
                        $matchesOthers = false;
                        break;
                    }
                }

                if ($matchesOthers) {
                    $val = $p[$col] ?? null;
                    if ($val !== null) {
                        $counts[$val] = ($counts[$val] ?? 0) + 1;
                    }
                }
            }

            $activeForCol = $activeFilters[$col] ?? [];

            $options = [];
            foreach ($counts as $val => $cnt) {
                $options[] = [
                    'value' => $val,
                    'count' => $cnt,
                ];
            }

            $existingValues = array_column($options, 'value');
            foreach ($activeForCol as $checkedVal) {
                if (!in_array($checkedVal, $existingValues, true)) {
                    $options[] = [
                        'value' => $checkedVal,
                        'count' => 0,
                    ];
                }
            }

            if (!empty($options)) {
                usort($options, fn($a, $b) => strnatcasecmp($a['value'], $b['value']));
                $specs[$col] = [
                    'label' => $label,
                    'options' => $options,
                ];
            }
        }
    }
@endphp

<div class="aside-catalogo-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #ee7c31; padding-bottom: 6px;">
        <h4 class="aside-title-custom" style="margin: 0; border: none; padding: 0;">Filtros por Especificaciones</h4>
        @php
            $hasAnyFilter = false;
            $allCheckKeys = array_merge(
                array_keys($specFields ?? []),
                array_keys($monitorSpecMap ?? []),
                array_keys($tonerSpecMap ?? []),
                ['numero_parte_toner']
            );
            foreach ($allCheckKeys as $k) {
                if (request()->filled($k)) { $hasAnyFilter = true; break; }
            }
        @endphp
        @if($hasAnyFilter)
            <button type="button" onclick="clearAllFilters(event)" style="background: none; border: none; color: #ee7c31; font-size: 12px; font-weight: 700; cursor: pointer; padding: 2px 6px; text-decoration: underline;" title="Restablecer filtros">
                Limpiar todo
            </button>
        @endif
    </div>

    <aside>
        @forelse($specs as $campo => $spec)
            @php
                $activeValues = request($campo) ? explode(',', (string)request($campo)) : [];
                $isOpen = !empty($activeValues);
            @endphp
            <div class="seccion_filtro" data-campo="{{ $campo }}">
                <div class="boton_filtros" style="font-size: 13.5px;" onclick="toggleFilter('{{ $campo }}')">
                    <button type="button" style="text-align: left; background: none; border: none; width: 100%; font-weight: 600; font-size: 13.5px; color: #000; padding: 0; outline: none; cursor: pointer;">
                        {{ $spec['label'] }}
                    </button>
                    <div class="icon_boton_filtros">
                        <i id="icon_{{ $campo }}" class="fa-solid {{ $isOpen ? 'fa-minus' : 'fa-plus' }}"></i>
                    </div>
                </div>

                @if(count($spec['options']) > 4)
                    <div class="filter-search-wrap" id="search_wrap_{{ $campo }}" style="display: {{ $isOpen ? 'block' : 'none' }};">
                        <input type="text" class="filter-search-input" data-campo="{{ $campo }}" placeholder="Buscar {{ $spec['label'] }}..." style="width:100%; padding:4px 8px; font-size:12px; border:1px solid #ddd; border-radius:4px; outline:none; box-sizing:border-box;">
                    </div>
                @endif

                <ul id="list_{{ $campo }}" class="lista" style="{{ $isOpen ? 'display: block;' : 'display: none;' }}">
                    @foreach($spec['options'] as $optItem)
                        @php
                            $opt = is_array($optItem) ? $optItem['value'] : $optItem;
                            $cnt = is_array($optItem) ? $optItem['count'] : null;
                            $isChecked = in_array($opt, $activeValues);
                        @endphp
                        <li class="item_producto" style="font-weight: bold; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox"
                                   id="chk_{{ $campo }}_{{ $loop->index }}"
                                   value="{{ $opt }}"
                                   {{ $isChecked ? 'checked' : '' }}
                                   onchange="submitFilter('{{ $campo }}', '{{ $opt }}', this.checked)"
                                   style="cursor: pointer; width: 16px; height: 16px; margin: 0; flex-shrink: 0;">
                            <label for="chk_{{ $campo }}_{{ $loop->index }}" style="font-size: 13px; line-height: 1.3; margin-bottom: 0; cursor: pointer; font-weight: 600; color: #444; flex: 1; display: flex; justify-content: space-between; align-items: baseline; gap: 6px;">
                                <span style="word-break: break-word;">{{ $opt }}</span>
                                @if($cnt !== null)
                                    <span class="filter-count" style="font-weight: 600; color: {{ $cnt > 0 ? '#ee7c31' : '#aaa' }}; font-size: 12px; white-space: nowrap; flex-shrink: 0;">({{ $cnt }})</span>
                                @endif
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div style="padding: 15px; color: #777; font-size: 13.5px; text-align: center;">
                No hay especificaciones disponibles para la selección actual.
            </div>
        @endforelse

        <!-- Botón Limpiar Filtros inferior -->
        <div style="margin-top: 15px; padding: 0 10px;">
            <button type="button" onclick="clearAllFilters(event)" class="btn btn-secondary btn-sm w-100" style="background-color: #6c757d; border-color: #6c757d; color: white; display: block; text-align: center; padding: 8px; border-radius: 4px; font-weight: bold; text-decoration: none; cursor: pointer; width: 100%;">
                <i class="fas fa-times-circle"></i> Limpiar Filtros
            </button>
        </div>
    </aside>
</div>

<script>
    function toggleFilter(campo) {
        const list = document.getElementById('list_' + campo);
        const icon = document.getElementById('icon_' + campo);
        const searchWrap = document.getElementById('search_wrap_' + campo);
        if (!list) return;
        if (list.style.display === 'none') {
            list.style.display = 'block';
            if (icon) { icon.classList.remove('fa-plus'); icon.classList.add('fa-minus'); }
            if (searchWrap) searchWrap.style.display = 'block';
        } else {
            list.style.display = 'none';
            if (icon) { icon.classList.remove('fa-minus'); icon.classList.add('fa-plus'); }
            if (searchWrap) searchWrap.style.display = 'none';
        }
    }

    document.querySelectorAll('.filter-search-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const campo = this.dataset.campo;
            const text = this.value.toLowerCase().trim();
            document.querySelectorAll('#list_' + campo + ' .item_producto').forEach(function(li) {
                const label = li.querySelector('label span');
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

        params.delete('page');

        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.replaceState({}, '', newUrl);
        window.dispatchEvent(new CustomEvent('filterchange'));
    }

    function clearAllFilters(e) {
        if (e) e.preventDefault();
        const modeloSelect = document.getElementById('preview-modelo');
        const mVal = modeloSelect ? modeloSelect.value : '';
        const params = new URLSearchParams();
        if (mVal) params.set('modelo', mVal);
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.replaceState({}, '', newUrl);
        window.dispatchEvent(new CustomEvent('filterchange'));
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
.item_producto:hover {
    background-color: #fcfcfc;
}
.filter-search-wrap {
    padding: 4px 8px;
}
.filter-search-input:focus {
    border-color: #ee7c31 !important;
    box-shadow: 0 0 0 2px rgba(238,124,49,0.15);
}
</style>
