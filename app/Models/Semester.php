<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Semester extends Model
{

    protected $fillable = [
        'semester',
        'number_weeks',
        'school_year',
        'semester_start_date',
        'semester_end_date',
        'user_create_id',
        'user_update_id',
    ];

    public function weeks()
    {
        return $this->hasMany(Week::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
