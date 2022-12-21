<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'customer_id', 'innerCode', 'outerCode', 'hourSW', 'hourMS', 'hourFAT', 'hourSAF', 'country_id', 'job_type_id', 'status_id', 'description', 'opening', 'closing', 'company_id', 'created_by',
    ];

    public function scopeFilter($query, array $filters): void
    {
        if ($filters['customer'] ?? false) {
            $query->where('customer_id', Customer::where('name', 'like', '%'.request('customer').'%')->value('id'));
        }

        if ($filters['company'] ?? false) {
            $query->where('company_id', Company::where('name', 'like', '%'.$filters['company'].'%')->value('id'));
        }

        if ($filters['search'] ?? false) {
            $query->where('customer_id', Customer::where('name', 'like', '%'.request('search').'%')->value('id'))
                    ->orWhere('user_id', User::where('name', 'like', '%'.request('search').'%')->value('id'))
                    ->orWhere('company_id', Company::where('name', 'like', '%'.$filters['search'].'%')->value('id'))
                    ->orWhere('country_id', Country::where('name', 'like', '%'.request('search').'%')->value('id'))
                    ->orWhere('status_id', Status::where('description', 'like', '%'.request('search').'%')->value('id'))
                    ->orWhere('description', 'like', '%'.request('search').'%')
                    ->orWhere('job_type_id', 'like', '%'.request('search').'%')
                    ->orWhere('innerCode', request('search'))
                    ->orWhere('outerCode', request('search'));
        }
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function job_type(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function technical_reports(): HasMany
    {
        return $this->hasMany(TechnicalReport::class, 'order_id');
    }

    public function engagements()
    {
        return $this->hasMany(Engagement::class, 'order_id');
    }

    public function getHours(int $job_type_id)
    {
        $temp = new Collection();
        foreach ($this->order_details->where('job_type_id', $job_type_id) as $detail) {
            $temp->push($detail->hour);
        }
        $data = [
            'count' => 0
        ];
        foreach ($temp->groupBy(function ($item) {
        return $item->user->name.' '.$item->user->surname;
        }) as $user => $item) {
            $data[$user] = 0;
            foreach ($item as $dato) {
                $data[$user] += $dato->count;
                $data['count'] += $dato->count;
            }
        }
        return collect($data);
    }
}
