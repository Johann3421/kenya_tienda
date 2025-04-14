<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function getProvince()
    {
        return $this->belongsTo('App\Models\Province', 'province_id', 'id')->with('getDepartment');;
    }
}
