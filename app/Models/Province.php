<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function getDistrict()
    {
        return $this->hasMany('App\Models\District', 'province_id', 'id');
    }

    public function getDepartment()
    {
        return $this->belongsTo('App\Models\Department', 'department_id', 'id');
    }
}
