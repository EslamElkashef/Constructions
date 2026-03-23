<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'role', 'avatar', 'favourite', 'background'];

    // 🔗 كل عضو ينتمي إلى مشروع واحد
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    // 🔗 المهام المرتبطة بمشروع العضو
    public function tasks()
    {
        return $this->hasManyThrough(
            Task::class,      // الموديل النهائي
            Project::class,   // الموديل الوسيط
            'id',             // المفتاح المحلي في projects (id)
            'project_id',     // المفتاح الأجنبي في tasks
            'project_id',     // المفتاح في team_members
            'id'              // المفتاح في projects
        );
    }

    public function getProjectsAttribute()
    {
        return collect([$this->project])->filter();
    }
}
