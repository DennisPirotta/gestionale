<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

class Hour extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour_type_id', 'user_id', 'count', 'date','description'
    ];

    public function scopeFilter($query, array $filters): void
    {
        if ($filters['month'] ?? false) {
            $period = CarbonPeriod::create(Carbon::parse(request('month'))->firstOfMonth(), Carbon::parse(request('month'))->lastOfMonth());
            $query->whereBetween('date', [$period->first(), $period->last()]);
        }
        if ($filters['user'] ?? true) {
            $query->where('user_id', request('user'));
        }
    }

    public function holidays(): HasMany
    {
        return $this->hasMany(Holiday::class, 'hour_id');
    }

    public function order_details(): HasOne
    {
        return $this->hasOne(OrderDetails::class, 'hour_id');
    }

    public function order_hour(): OrderDetails|null
    {
        return OrderDetails::where('hour_id', $this->id)->first();
    }

    public function technical_report_details(): HasMany
    {
        return $this->hasMany(TechnicalReportDetails::class, 'hour_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hour_type(): BelongsTo
    {
        return $this->belongsTo(HourType::class, 'hour_type_id');
    }

    public function technical_report_hour(): TechnicalReportDetails|null
    {
        return TechnicalReportDetails::with('technical_report', 'hour')->where('hour_id', $this->id)->get()->first();
    }
}
