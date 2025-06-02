<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaginaEstado extends Model
{
    protected $fillable = ['ruta', 'nombre', 'estado', 'fin_mantenimiento'];
}
