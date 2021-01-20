<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

}
