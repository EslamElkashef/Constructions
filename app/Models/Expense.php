<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'amount',
        'category',
        'expense_date', // اللي في الميجريشن
        'notes',        // بدل description
        'user_id',
    ];

    protected $casts = [
        'expense_date' => 'date', // بدل spent_at
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
