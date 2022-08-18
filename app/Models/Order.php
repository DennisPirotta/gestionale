<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PHP_CodeSniffer\Tests\Core\File\testFIINNamespacedClass;

class Order extends Model
{
    use HasFactory;

    public function scopeFilter($query, array $filters): void
    {

        if ($filters['customer'] ?? false) {
            $customers = [];
            foreach (DB::table('customers')->where('name', 'like', '%' . $filters['customer'] . '%')->get() as $customer) {
                $customers[] = $customer->id;
            }
            $query->whereIn('customer_id', $customers);
        }

        if ($filters['company'] ?? false) {
            $companies = [];
            foreach (DB::table('companies')->where('name', 'like', '%' . $filters['company'] . '%')->get() as $company) {
                $companies[] = $company->id;
            }
            $query->whereIn('company_id', $companies);
        }

        if ($filters['search'] ?? false) {

            $customers = [];
            foreach (DB::table('customers')->where('name', 'like', '%' . $filters['search'] . '%')->get() as $customer) {
                $customers[] = $customer->id;
            }

            $companies = [];
            foreach (DB::table('companies')->where('name', 'like', '%' . $filters['search'] . '%')->get() as $company) {
                $companies[] = $company->id;
            }

            $countries = [];
            foreach (DB::table('countries')->where('name', 'like', '%' . $filters['search'] . '%')->get() as $country) {
                $countries[] = $country->id;
            }

            $statuses = [];
            foreach (DB::table('statuses')->where('description', 'like', '%' . $filters['search'] . '%')->get() as $status) {
                $statuses[] = $status->id;
            }

            $users = [];
            foreach (DB::table('users')->where('name', 'like', '%' . $filters['search'] . '%')->get() as $user) {
                $users[] = $user->id;
            }

            $query->whereIn('customer_id', $customers)
                ->orWhereIn('user_id', $users)
                ->orWhereIn('company_id', $companies)
                ->orWhereIn('country_id', $countries)
                ->orWhereIn('status_id', $statuses)
                ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                ->orWhere('progress', 'like', '%' . $filters['search'] . '%');
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

}
