<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Week extends Model
{
    protected $fillable = [
        'semester_id',
        'week',
        'start_day',
        'end_day',
        'note',
        'user_create_id',
        'user_update_id',
    ];


    protected $dates = ['deleted_at'];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
