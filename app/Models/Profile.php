<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'city',
        'country',
        'designation',
        'description',
        'avatar',
        'favourite',
        'status',
        'status_reason',
    ];

    protected $casts = [
        'favourite' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/'.$this->avatar)
            : asset('assets/images/users/avatar-1.jpg');
    }
}
