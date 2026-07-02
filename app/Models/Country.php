<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'iso3',
        'iso2',
        'phone_code',
        'capital',
        'currency',
        'currency_symbol',
        'latitude',
        'longitude',
        'region',
        'subregion',
    ];
}
