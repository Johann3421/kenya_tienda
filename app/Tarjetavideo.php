<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarjetavideo extends Model
{
    protected $table = 'tarjetadevideo';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
