<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'address', 'phone', 'employee_id', 'unit_type_id', 'status', 'city', 'sold_at'];

    public function details()
    {
        return $this->hasMany(UnitDetail::class);
    }

    public function media()
    {
        return $this->hasMany(UnitMedia::class);
    }

    public function type()
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // علاقة الفواتير
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
