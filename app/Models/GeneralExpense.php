<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralExpense extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'category_id',
        'amount',
        'expense_date',
        'payment_method',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    // علاقة بالمشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // علاقة بالمستخدم اللي صرف
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // علاقة بالفئة
    public function category()
    {
        return $this->belongsTo(GeneralExpenseCategory::class, 'category_id');
    }
}
