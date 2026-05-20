@php
    use App\Producto;

    $modId = $id ?? (request()->route('id') ?? request()->route('modelo'));

    $specFields = [
        'procesador'       => 'Procesador',
        'ram'              => 'Memoria RAM',
        'almacenamiento'   => 'Almacenamiento',
        'sistema_operativo'=> 'Sistema Operativo',
        'unidad_optica'    => 'Unidad Óptica',
        'conectividad'     => 'Conectividad LAN',
        'conectividad_wlan'=> 'Conectividad WLAN',
        'conectividad_usb' => 'Conectividad USB',
        'video_vga'        => 'Salida VGA',
        'video_hdmi'       => 'Salida HDMI',
        'suite_ofimatica'  => 'Ofimática',
    ];

    $specs = [];
    foreach ($specFields as $col => $label) {
        $values = Producto::select($col)
            ->where('modelo_id', $modId)
            ->where('pagina_web', 'SI')
            ->distinct()
            ->whereNotNull($col)
            ->where($col, '!=', '')
            ->orderBy($col)
            ->pluck($col);
        if ($values->isNotEmpty()) {
            $specs[$col] = ['label' => $label, 'options' => $values];
        }
    }
@endphp

<div class="aside-catalogo">
    <h4 class="aside-title">Filtros por Especificaciones</h4>
    <form method="GET" action="{{ url('catalogo/' . $modId . '/detallemod') }}" id="filtros-detallemod-form">
        @foreach(request()->except(array_keys($specFields)) as $key => $val)
            @if(is_array($val))
                @foreach($val as $v)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endif
        @endforeach

        @foreach($specs as $campo => $spec)
        <div class="filtro-group">
            <label for="filtro_{{ $campo }}">{{ $spec['label'] }}</label>
            <select name="{{ $campo }}"
                    id="filtro_{{ $campo }}"
                    class="form-control filtro-select"
                    onchange="document.getElementById('filtros-detallemod-form').submit()">
                <option value="">Todos</option>
                @foreach($spec['options'] as $opt)
                    <option value="{{ $opt }}" {{ request($campo) == $opt ? 'selected' : '' }}>
                        {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>
        @endforeach

        <!-- Limpiar Filtros -->
        <div class="filtro-group">
            <a href="{{ url('catalogo/' . $modId . '/detallemod') }}" class="btn btn-secondary btn-sm w-100">
                <i class="fas fa-times-circle"></i> Limpiar Filtros
            </a>
        </div>
    </form>
</div>

<style>
.aside-catalogo {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 0.5rem;
}
.aside-title {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.4rem;
}
.filtro-group {
    margin-bottom: 0.9rem;
}
.filtro-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.3rem;
    color: #555;
    font-size: 0.85rem;
}
.filtro-select {
    width: 100%;
    padding: 0.4rem 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.85rem;
    background: #fff;
}
.filtro-select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,123,255,.15);
}
</style>
