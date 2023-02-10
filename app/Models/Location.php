<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'description', 'user_id','sph_office'
    ];

    public function scopeFilter($query, array $filters): void
    {
        if ($filters['user'] ?? false) {
            $query->where('user_id', request('user'));
        }
        if($filters['sph'] ?? false) {
            $query->where('sph_office',true);
        }
    }

    public static function getSphOfficeData(): array
    {
        $exceptions = [];
        foreach (Location::all() as $location){
            if ($location->sph_office){
                $exceptions[] = $location->date;
            }
        }
        return $exceptions;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
