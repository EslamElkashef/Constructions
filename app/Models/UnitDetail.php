<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitDetail extends Model
{
    protected $fillable = ['unit_id', 'field', 'value'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
