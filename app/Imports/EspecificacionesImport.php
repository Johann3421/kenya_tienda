<?php

namespace App\Imports;

use App\Producto;
use App\Models\Especificacion;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class EspecificacionesImport implements ToModel
{
    protected $producto;

    public function __construct(Producto $producto)
    {
        $this->producto = $producto;
    }

    public function model(array $row)
    {
        Log::info('Procesando fila:', $row);

        return new Especificacion([
            'campo' => $row[0] ?? 'Sin nombre', // Primera columna
            'descripcion' => $row[1] ?? '',    // Segunda columna
            'producto_id' => $this->producto->id,
        ]);
    }
}
