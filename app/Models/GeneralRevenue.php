<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralRevenue extends Model
{
    protected $fillable = [
        'title',
        'received_from',
        'amount',
        'category',
        'project_id',
        'unit_id',
        'payment_method',
        'reference_number',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
