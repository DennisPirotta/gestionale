<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            $query->whereIn('customer', $customers);
        }

        if ($filters['company'] ?? false) {
            $companies = [];
            foreach (DB::table('companies')->where('name', 'like', '%' . $filters['company'] . '%')->get() as $company) {
                $companies[] = $company->id;
            }
            $query->whereIn('company', $companies);
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

            $query->whereIn('customer', $customers)
                ->orWhereIn('manager', $users)
                ->orWhereIn('company', $companies)
                ->orWhereIn('country', $countries)
                ->orWhereIn('status', $statuses)
                ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                ->orWhere('progress', 'like', '%' . $filters['search'] . '%');
        }
    }
}
