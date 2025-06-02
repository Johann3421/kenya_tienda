{{-- filepath: resources/views/sistema/Control_rutas/index.blade.php --}}
@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Administración de Páginas</h3>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover shadow-sm bg-white">
        <thead class="thead-dark">
            <tr>
                <th>Página</th>
                <th>Ruta</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paginas as $pagina)
            <tr>
                <td><strong>{{ $pagina->nombre }}</strong></td>
                <td><code>{{ $pagina->ruta }}</code></td>
                <td>
                    @if($pagina->estado === 'activo')
                        <span class="badge badge-success px-3 py-2">Activo</span>
                    @else
                        <span class="badge badge-warning px-3 py-2">Mantenimiento</span>
                    @endif
                </td>
                <td>
    <form action="{{ route('paginas.admin.cambiar_estado') }}" method="POST" class="d-inline">
        @csrf
        <input type="hidden" name="ruta" value="{{ $pagina->ruta }}">
        <input type="hidden" name="estado" value="{{ $pagina->estado === 'activo' ? 'mantenimiento' : 'activo' }}">
        @if($pagina->estado === 'activo')
            <input type="datetime-local" name="fin_mantenimiento" class="form-control form-control-sm mb-2" required>
        @endif
        <button type="submit" class="btn btn-{{ $pagina->estado === 'activo' ? 'warning' : 'success' }} btn-sm">
            {{ $pagina->estado === 'activo' ? 'Poner en Mantenimiento' : 'Activar Página' }}
        </button>
    </form>
    @if($pagina->estado === 'mantenimiento' && $pagina->fin_mantenimiento)
        <div class="small text-muted mt-1">
            Fin: {{ \Carbon\Carbon::parse($pagina->fin_mantenimiento)->format('d/m/Y H:i') }}
        </div>
    @endif
</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if ($paginas->hasPages())
        <nav>
            <ul class="pagination justify-content-center">
                {{-- Botón anterior --}}
                <li class="page-item {{ $paginas->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginas->previousPageUrl() ?? '#' }}" tabindex="-1">Anterior</a>
                </li>
                {{-- Números de página --}}
                @foreach ($paginas->getUrlRange(1, $paginas->lastPage()) as $page => $url)
                    <li class="page-item {{ $paginas->currentPage() == $page ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                {{-- Botón siguiente --}}
                <li class="page-item {{ !$paginas->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $paginas->nextPageUrl() ?? '#' }}">Siguiente</a>
                </li>
            </ul>
        </nav>
    @endif
</div>
@endsection
