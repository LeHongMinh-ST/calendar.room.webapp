<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    const IS_ACTIVE = [
        'active' => 1,
        'deactivate' => 0
    ];

    protected $fillable = [
        'room_id',
        'name',
        'computer_number',
        'address',
        'subject',
        'software',
        'is_active',
        'user_create_id',
        'user_update_id',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('room_id', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
