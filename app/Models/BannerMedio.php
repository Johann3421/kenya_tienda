<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerMedio extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'banner_medios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'imagen_path',
        'url_destino',
        'activo',
        'orden',
        'posicion'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];

    /**
     * Scope para banners activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para una posición específica
     */
    public function scopePosicion($query, $posicion)
    {
        return $query->where('posicion', $posicion);
    }
    
}
