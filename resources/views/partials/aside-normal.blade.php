@php
    use App\Modelo;

    $modeloId = request()->route('id') ?? request()->route('modelo');
    $modelo = $modeloId ? Modelo::with('asides')->find($modeloId) : null;

    $filtros = $modelo ? $modelo->asides->where('activo', true)->filter(fn($f) => !empty($f->opciones)) : collect();

    $jsonFiltros = $filtros
        ->map(function ($f) {
            return [
                'slug' => Str::slug($f->nombre_aside),
                'nombre' => $f->nombre_aside,
                'opciones' => $f->opciones ?? [],
            ];
        })
        ->values();
@endphp

<div id="vue-filtros" data-filtros='@json($jsonFiltros)'>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.min.js"></script>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const filtrosData = JSON.parse(document.getElementById('vue-filtros').dataset.filtros);

        new Vue({
            el: '#vue-filtros',
            data: {
                filtros: filtrosData,
                desplegar: {},
                filtrosActivos: {}
            },
            created() {
                const params = new URLSearchParams(window.location.search);

                this.filtros.forEach(f => {
                    this.$set(this.desplegar, f.slug, false);
                    const param = params.get(f.slug);
                    if (param) {
                        this.$set(this.filtrosActivos, f.slug, param.split(','));
                    } else {
                        this.$set(this.filtrosActivos, f.slug, []);
                    }
                });
            },
            methods: {
                Desplegar(slug) {
                    this.desplegar[slug] = !this.desplegar[slug];
                },
                Filtrar(valor, categoria, event) {
                    if (!this.filtrosActivos[categoria]) {
                        this.$set(this.filtrosActivos, categoria, []);
                    }

                    if (event.target.checked) {
                        this.filtrosActivos[categoria].push(valor);
                    } else {
                        const index = this.filtrosActivos[categoria].indexOf(valor);
                        if (index !== -1) {
                            this.filtrosActivos[categoria].splice(index, 1);
                        }
                    }

                    const query = new URLSearchParams();
                    for (const cat in this.filtrosActivos) {
                        if (this.filtrosActivos[cat].length > 0) {
                            query.set(cat, this.filtrosActivos[cat].join(','));
                        }
                    }

                    const newUrl = `${window.location.pathname}?${query.toString()}`;
                    window.history.pushState({}, '', newUrl);
                    window.location.reload(); // recargar para que Laravel filtre
                },
                isChecked(cat, val) {
                    return this.filtrosActivos[cat] && this.filtrosActivos[cat].includes(val);
                }
            },
            template: `
            <aside>
                <div v-for="filtro in filtros" :key="filtro.slug" class="seccion_filtro">
                    <div class="boton_filtros" style="font-size: 13.5px;" @click="Desplegar(filtro.slug)">
                        <button>@{{ filtro.nombre }}</button>
                        <div class="icon_boton_filtros">
                            <i class="fa-solid" :class="desplegar[filtro.slug] ? 'fa-minus' : 'fa-plus'"></i>
                        </div>
                    </div>

                    <ul v-show="desplegar[filtro.slug]" class="lista">
                        <li v-for="opcion in filtro.opciones" :key="opcion" class="item_producto" style="font-weight: bold">
                            <input type="checkbox"
                                   :name="filtro.slug"
                                   :value="opcion"
                                   :checked="isChecked(filtro.slug, opcion)"
                                   @change="Filtrar(opcion, filtro.slug, $event)">
                            <label style="font-size: 14px;">@{{ opcion }}</label>
                        </li>
                    </ul>
                </div>
            </aside>
            `
        });
    });
</script>
