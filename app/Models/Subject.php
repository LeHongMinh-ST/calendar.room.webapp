<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    protected $fillable = [
        'subject_id',
        'department_id',
        'name',
        'is_active',
        'user_create_id',
        'user_update_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('subject_id', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
