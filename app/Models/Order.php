<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PHP_CodeSniffer\Tests\Core\File\testFIINNamespacedClass;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id','customer_id'
    ];

    public function scopeFilter($query, array $filters): void
    {

        if ($filters['customer'] ?? false) {
            $query->where('customer_id', Customer::where('name', 'like', '%' . request('customer') . '%')->value('id'));
        }

        if ($filters['company'] ?? false) {
            $query->where('company_id', Company::where('name', 'like', '%' . $filters['company'] . '%')->value('id'));
        }

        if ($filters['search'] ?? false) {


            $query  ->where  ('customer_id', Customer::where('name', 'like', '%' . request('search') . '%')->value('id'))
                    ->orWhere('user_id', User::where('name', 'like', '%' . request('search') . '%')->value('id'))
                    ->orWhere('company_id', Company::where('name', 'like', '%' . $filters['search'] . '%')->value('id'))
                    ->orWhere('country_id', Country::where('name', 'like', '%' . request('search') . '%')->value('id'))
                    ->orWhere('status_id', Status::where('description', 'like', '%' . request('search') . '%')->value('id'))
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('job_type_id', 'like', '%' . request('search') . '%');
        }
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }

    public function status(){
        return $this->belongsTo(Status::class,'status_id');
    }

    public function job_type(){
        return $this->belongsTo(JobType::class,'job_type_id');
    }

    public function order_details(){
        return $this->hasMany(OrderDetails::class,'order_id');
    }

}
