<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'utc_offset_seconds',
        'open_time_local',
        'close_time_local'
    ];

    protected $hidden = [
        'deleted'
    ];

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }
}
