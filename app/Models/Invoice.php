<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'unit_id',
        'employee_id',
        'buyer_name',
        'seller_name',
        'unit_price',
        'meter_price',
        'offer_date',
        'sale_date',
        'company_commission_from_buyer',
        'company_commission_from_seller',
        'employee_commission_value',
        'employee_commission_percent',
        'commission_settlement',
        'status',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
