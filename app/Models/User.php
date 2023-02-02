<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'position',
        'level',
        'company_id',
        'holidays',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, ['user_id', 'created_by']);
    }

    public function holidayList(): HasMany
    {
        return $this->hasMany(Holiday::class, 'user_id');
    }

    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class, 'user_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class, 'user_id');
    }

    public function technical_reports(): HasMany
    {
        return $this->hasMany(TechnicalReport::class, 'user_id');
    }

    public function business_hours(): HasMany
    {
        return $this->hasMany(BusinessHour::class, 'user_id');
    }

    public function bug_reports(): HasMany
    {
        return $this->hasMany(BugReport::class, 'reported_by');
    }

    public function expense_reports(): HasMany
    {
        return $this->hasMany(ExpenseReport::class, 'user_id');
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(Engagement::class, 'user_id');
    }

    public function getLeftHolidays(): int
    {
        $holidays = Holiday::where('user_id', $this->id)
                            ->whereBetween('start', [Carbon::now()->firstOfYear(), Carbon::now()->lastOfYear()])
                            ->whereBetween('end', [Carbon::now()->firstOfYear(), Carbon::now()->lastOfYear()]);
        $count = 0;
        Carbon::setOpeningHours(BusinessHour::getWorkingHours($this));
        foreach ($holidays->get() as $holiday) {
            $count += Carbon::parse($holiday->start)->diffInBusinessHours($holiday->end);
        }

        return $this->holidays - $count;
    }

    public function hourDetails(CarbonPeriod $period): array
    {
        $data = [
            'total' => 0,
            'holidays' => 0,
            'eu' => 0,
            'xeu' => 0,
            'str25' => 0,
            'str50' => 0,
        ];

        foreach ($period as $day) {
            $daily = $this->hoursInDay($day);
            $data['total'] += $daily['total'];
            $data['holidays'] += $daily['holidays'];
            $data['eu'] += $daily['eu'];
            $data['xeu'] += $daily['xeu'];
            $data['str25'] += $daily['str25'];
            $data['str50'] += $daily['str50'];
        }

        return $data;
    }

    public function hoursInPeriod(CarbonPeriod $period)
    {
        return $this->hours->filter(static function ($item) use ($period) {
            return Carbon::parse($item->date)->isBetween(clone $period->first(), $period->last());
        });
    }

    public function hoursInDay(Carbon $day): array
    {
        $hours = $this->hours->where('date', $day->format('Y-m-d'));
        $data = [
            'total' => 0,
            'holidays' => 0,
            'eu' => 0,
            'xeu' => 0,
            'str25' => 0,
            'str50' => 0,
        ];
        foreach ($hours as $hour) {
            if ($hour->hour_type_id === 2) {
                if ($hour->technical_report_details->first() === null) {
                    $hour->delete();
                    break;
                } else {
                    if ($hour->technical_report_details->first()->nightEU) {
                        $data['eu']++;
                    }
                    if ($hour->technical_report_details->first()->nightExtraEU) {
                        $data['xeu']++;
                    }
                }
            }
            $data['total'] += $hour->count;
            if ($day->isHoliday() || $day->isWeekend()) {
                $data['str50'] += $hour->count;
            }else{
                $data['str25'] = $data['total'] - 8;
            }

            if ($hour->hour_type_id === 6) {
                $data['holidays'] += $hour->count;
            }
        }
        if ($hours->isEmpty() && $day->isPast() && !($day->isWeekend() || $day->isHoliday())){
            $data['str25'] = $data['total'] - 8;
        }
        return $data;
    }
}
