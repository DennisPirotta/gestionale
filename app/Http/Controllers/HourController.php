<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class HourController extends Controller
{
    public function index(): Factory|View|Application
    {

        $hours = Hour::all();
        $hoursType = HourType::all();


        $formatted = [];
        foreach ($hours as $hour){

            $content = $hour->description ?? null;

            $formatted[] = [
              'title' => $hoursType->where('id',$hour->hour_type)->pluck('description'),
              'start' => $hour->start,
              'end' => $hour->end,
              'extendedProps' => [
                  'content' => $content
              ]
            ];
        }

        return view('hours.index',[
            'hours' => $formatted
        ]);
    }

    public function create(): Application|View|Factory
    {
        return view('hours.create',[
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'customers' => Customer::all(),
            'orders' => Order::all()->sortBy('status'),
            'statuses' => Status::all()
        ]);
    }
    public function edit()
    {}
    public function store()
    {}
    public function update()
    {}
    public function destroy()
    {}
}
