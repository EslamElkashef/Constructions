<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitMedia extends Model
{
    protected $fillable = ['unit_id', 'type', 'path'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
