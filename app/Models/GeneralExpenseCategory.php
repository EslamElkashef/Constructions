<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralExpenseCategory extends Model
{
    protected $fillable = ['name'];

    public function expenses()
    {
        return $this->hasMany(\App\Models\GeneralExpense::class, 'category_id');
    }
}
