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
        'company_id'
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
        return $this->belongsTo(Company::class,'company_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class,['user_id','created_by']);
    }

    public function holidayList(): HasMany
    {
        return $this->hasMany(Holiday::class,'user_id');
    }
    public function hours(): HasMany
    {
        return $this->hasMany(Hour::class,'user_id');
    }
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class,'user_id');
    }
    public function technical_reports(): HasMany
    {
        return $this->hasMany(TechnicalReport::class,'user_id');
    }
    public function business_hours(): HasMany
    {
        return $this->hasMany(BusinessHour::class,'user_id');
    }

    public function bug_reports(): HasMany
    {
        return $this->hasMany(BugReport::class,'reported_by');
    }

    public function expense_reports(): HasMany
    {
        return $this->hasMany(ExpenseReport::class,'customer_id');
    }

    public function getLeftHolidays(): int
    {
        $holidays = Holiday::where('user_id',$this->id)->get();
        $count = 0;
        Carbon::setOpeningHours(BusinessHour::getWorkingHours($this));
        foreach ($holidays as $holiday){
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
            'festive' => 0,
        ];
        $test = Carbon::now();



        foreach ($this->hoursInPeriod($period) as $hour){
            $data['total'] += $hour->count;
            if (Carbon::parse($hour->date)->isHoliday()){
                $data['festive'] += $hour->count;
            }
            if ($hour->hour_type_id === 6){
                $data['holidays'] += $hour->count;
            }
            if ($hour->hour_type_id === 2){
                $report = TechnicalReportDetails::where('hour_id',$hour->id)->first();
                if ($report->nightEU === 1){
                    $data['eu'] += $hour->count;
                }
                if ($report->nightExtraEU === 1){
                    $data['xeu'] += $hour->count;
                }
            }
        }

        return $data;
    }

    public function hoursInPeriod(CarbonPeriod $period){
        return $this->hours->filter(static function($item) use ($period){ return Carbon::parse($item->date)->isBetween(clone $period->first(),$period->last());});
    }
}
