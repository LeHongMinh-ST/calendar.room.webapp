<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
