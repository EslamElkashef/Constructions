<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_title',
        'company_name',
        'from_year',
        'to_year',
        'job_description',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
