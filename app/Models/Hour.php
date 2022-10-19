<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
      'hour_type_id', 'user_id','count','date'
    ];

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class,'hour_id');
    }
    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetails::class,'hour_id');
    }
    public function technical_report_details(): HasMany
    {
        return $this->hasMany(TechnicalReportDetails::class,'hour_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function hour_type(): BelongsTo
    {
        return $this->belongsTo(HourType::class,'hour_type_id');
    }

}
