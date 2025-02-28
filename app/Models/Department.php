<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function getProvinces()
    {
        return $this->hasMany('App\Models\Province', 'department_id', 'id')->with('getDistrict');;
    }
}
