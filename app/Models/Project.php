<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'thumbnail', 'description', 'priority', 'status', 'deadline',
        'privacy_status', 'categories', 'skills', 'attached_files', 'budget', 'user_id',
    ];

    protected $casts = [
        'attached_files' => 'array',
        'deadline' => 'date',
        'features' => 'array',
    ];

    protected $attributes = [
        'attached_files' => '[]',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'project_id');
    }

    // ✅ الـ relation الأساسي بدون withTrashed
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // ✅ relation منفصل للـ tasks المحذوفة (لو احتجتها)
    public function allTasksWithTrashed()
    {
        return $this->hasMany(Task::class)->withTrashed();
    }

    public function getTasksCountAttribute()
    {
        return $this->tasks()->count();
    }

    public function getTasksCompletedAttribute()
    {
        return $this->tasks()->where('status', 'completed')->count();
    }

    public function getProgressPercentageAttribute()
    {
        $total = $this->tasks_count;

        return $total > 0 ? round(($this->tasks_completed / $total) * 100) : 0;
    }

    // public function expenses()
    // {
    //     return $this->hasMany(Expense::class);
    // }

    public function generalExpenses()
    {
        return $this->hasMany(GeneralExpense::class, 'project_id');
    }
}
