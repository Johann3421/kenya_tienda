<?php
// app/Models/Especificacion.php
namespace App\Models;

use App\Producto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especificacion extends Model
{
    use HasFactory;

    protected $table = 'especificaciones';

    protected $fillable = [
        'campo',
        'descripcion',
        'producto_id'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public $timestamps = false;
}
