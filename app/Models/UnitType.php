<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    protected $fillable = ['name', 'name_ar', 'fields'];

    protected $casts = ['fields' => 'array'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
