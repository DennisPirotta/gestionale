<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HourType extends Model
{
    use HasFactory;

    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class, 'hour_type_id');
    }
}
