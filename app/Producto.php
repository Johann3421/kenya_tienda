<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Marca;
use App\Producto_drivers;

class Producto extends Model
{

    //protected $fillable = ['nombre', 'nombre_secundario', 'descripcion', 'nro_parte','procesador',
      //  'ram','almacenamiento','conectividad','conectividad_wlan','conectividad_usb','video_vga',
       // 'video_hdmi','sistema_operativo','unidad_optica','teclado','mouse','suite_ofimatica',
        //'garantia_de_fabrica','empaque_fabrica','certificacion','unidad', 'moneda', 'precio_unitario',
        //'tipo_afectacion','categoria', 'marca', 'cantidad_por_precio', 'incluye_igv', 'codigo_interno',
        //'codigo_sunat','maneja_lotes', 'maneja_series', 'incluye_percepcion', 'linea_producto', 'imagen', 'modelo'];
    protected $table = 'productos';

    public function getCategoria()
    {
        return $this->hasOne(Categoria::class, 'id', 'categoria_id');
    }

    public function getMarca()
    {
        return $this->hasOne(Marca::class, 'id', 'marca_id');
    }
    public function getDrivers()
    {
        return $this->hasMany(Producto_drivers::class,'producto_id','id');
    }
    public function getManual()
    {
        return $this->hasMany(Manual::class,'producto_id','id');
    }
    public function getGarantia()
    {
        return $this->hasMany(Garantia::class,'producto_id','id');
    }
    public function getModelo()
    {
        return $this->hasOne(Modelo::class, 'id', 'modelo_id');
    }

}
