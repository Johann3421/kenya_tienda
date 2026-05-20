<div class="aside-catalogo">
    <h4 class="aside-title">Filtros por Especificaciones Técnicas</h4>
    <form method="GET" action="{{ route('catalogo') }}" id="filtros-form">
        <!-- Mantener parámetros existentes -->
        <input type="hidden" name="busqueda" value="{{ request('busqueda') }}">
        <input type="hidden" name="modelo" value="{{ request('modelo') }}">
        <input type="hidden" name="orden" value="{{ request('orden') }}">

        <!-- Filtro Procesador -->
        @if($procesadores->count() > 0)
        <div class="filtro-group">
            <label for="procesador">Procesador</label>
            <select name="procesador" id="procesador" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todos</option>
                @foreach($procesadores as $proc)
                    <option value="{{ $proc }}" {{ request('procesador') == $proc ? 'selected' : '' }}>{{ $proc }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Memoria RAM -->
        @if($memorias_ram->count() > 0)
        <div class="filtro-group">
            <label for="memoria_ram">Memoria RAM</label>
            <select name="memoria_ram" id="memoria_ram" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($memorias_ram as $ram)
                    <option value="{{ $ram }}" {{ request('memoria_ram') == $ram ? 'selected' : '' }}>{{ $ram }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Almacenamiento -->
        @if($almacenamientos->count() > 0)
        <div class="filtro-group">
            <label for="almacenamiento">Almacenamiento</label>
            <select name="almacenamiento" id="almacenamiento" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todos</option>
                @foreach($almacenamientos as $alm)
                    <option value="{{ $alm }}" {{ request('almacenamiento') == $alm ? 'selected' : '' }}>{{ $alm }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Sistema Operativo -->
        @if($sistemas_operativos->count() > 0)
        <div class="filtro-group">
            <label for="sistema_operativo">Sistema Operativo</label>
            <select name="sistema_operativo" id="sistema_operativo" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todos</option>
                @foreach($sistemas_operativos as $so)
                    <option value="{{ $so }}" {{ request('sistema_operativo') == $so ? 'selected' : '' }}>{{ $so }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Unidad Óptica -->
        @if($unidades_opticas->count() > 0)
        <div class="filtro-group">
            <label for="unidad_optica">Unidad Óptica</label>
            <select name="unidad_optica" id="unidad_optica" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($unidades_opticas as $uo)
                    <option value="{{ $uo }}" {{ request('unidad_optica') == $uo ? 'selected' : '' }}>{{ $uo }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Conectividad LAN -->
        @if($conectividades_lan->count() > 0)
        <div class="filtro-group">
            <label for="conectividad_lan">Conectividad LAN</label>
            <select name="conectividad_lan" id="conectividad_lan" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($conectividades_lan as $lan)
                    <option value="{{ $lan }}" {{ request('conectividad_lan') == $lan ? 'selected' : '' }}>{{ $lan }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Conectividad WLAN -->
        @if($conectividades_wlan->count() > 0)
        <div class="filtro-group">
            <label for="conectividad_wlan">Conectividad WLAN</label>
            <select name="conectividad_wlan" id="conectividad_wlan" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($conectividades_wlan as $wlan)
                    <option value="{{ $wlan }}" {{ request('conectividad_wlan') == $wlan ? 'selected' : '' }}>{{ $wlan }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Conectividad USB -->
        @if($conectividades_usb->count() > 0)
        <div class="filtro-group">
            <label for="conectividad_usb">Conectividad USB</label>
            <select name="conectividad_usb" id="conectividad_usb" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($conectividades_usb as $usb)
                    <option value="{{ $usb }}" {{ request('conectividad_usb') == $usb ? 'selected' : '' }}>{{ $usb }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Conectividad VGA -->
        @if($conectividades_vga->count() > 0)
        <div class="filtro-group">
            <label for="conectividad_vga">Conectividad VGA</label>
            <select name="conectividad_vga" id="conectividad_vga" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($conectividades_vga as $vga)
                    <option value="{{ $vga }}" {{ request('conectividad_vga') == $vga ? 'selected' : '' }}>{{ $vga }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Conectividad HDMI -->
        @if($conectividades_hdmi->count() > 0)
        <div class="filtro-group">
            <label for="conectividad_hdmi">Conectividad HDMI</label>
            <select name="conectividad_hdmi" id="conectividad_hdmi" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($conectividades_hdmi as $hdmi)
                    <option value="{{ $hdmi }}" {{ request('conectividad_hdmi') == $hdmi ? 'selected' : '' }}>{{ $hdmi }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Ofimática -->
        @if($ofimaticas->count() > 0)
        <div class="filtro-group">
            <label for="ofimatica">Ofimática</label>
            <select name="ofimatica" id="ofimatica" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($ofimaticas as $ofi)
                    <option value="{{ $ofi }}" {{ request('ofimatica') == $ofi ? 'selected' : '' }}>{{ $ofi }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Periféricos -->
        @if($perifericos_list->count() > 0)
        <div class="filtro-group">
            <label for="perifericos">Periféricos</label>
            <select name="perifericos" id="perifericos" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todos</option>
                @foreach($perifericos_list as $per)
                    <option value="{{ $per }}" {{ request('perifericos') == $per ? 'selected' : '' }}>{{ $per }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Filtro Tarjeta de Video -->
        @if(isset($tarjetas_video) && $tarjetas_video->count() > 0)
        <div class="filtro-group">
            <label for="tarjeta_video">Tarjeta de Video</label>
            <select name="tarjeta_video" id="tarjeta_video" class="form-control filtro-select" onchange="document.getElementById('filtros-form').submit()">
                <option value="">Todas</option>
                @foreach($tarjetas_video as $tv)
                    <option value="{{ $tv }}" {{ request('tarjeta_video') == $tv ? 'selected' : '' }}>{{ $tv }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Botón Limpiar Filtros -->
        <div class="filtro-group">
            <a href="{{ route('catalogo') }}?{{ http_build_query(collect(request()->query())->except(['procesador', 'memoria_ram', 'almacenamiento', 'sistema_operativo', 'unidad_optica', 'conectividad_lan', 'conectividad_wlan', 'conectividad_usb', 'conectividad_vga', 'conectividad_hdmi', 'ofimatica', 'perifericos', 'tarjeta_video'])->toArray()) }}" class="btn btn-secondary btn-sm">Limpiar Filtros</a>
        </div>
    </form>
</div>

<style>
.aside-catalogo {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.aside-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #333;
}

.filtro-group {
    margin-bottom: 1rem;
}

.filtro-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #555;
}

.filtro-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9rem;
}

.filtro-select:focus {
    border-color: #007bff;
    outline: none;
}
</style>
