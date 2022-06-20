<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use SoftDeletes;

    const IS_ACTIVE = [
        'active' => 1,
        'deactivate' => 0
    ];

    protected $fillable = [
        'faculty_id',
        'name',
        'user_create_id',
        'user_update_id',
        'is_active',
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('faculty_id', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
