<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku', 'name', 'description', 'cost_price', 'sell_price', 'quantity', 'low_stock_threshold',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function isLowStock(): bool
    {
        return $this->low_stock_threshold > 0 && $this->quantity <= $this->low_stock_threshold;
    }
}
