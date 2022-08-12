<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Holiday;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class HourController extends Controller
{
    public function index(): Factory|View|Application
    {

        $hours = Hour::all();


        $formatted = [];
        foreach ($hours as $hour){

            $start = explode(":",explode(" ",$hour->start)[1])[0] . ":" . explode(":",explode(" ",$hour->start)[1])[1];
            $end = explode(":",explode(" ",$hour->end)[1])[0] . ":" . explode(":",explode(" ",$hour->end)[1])[1];

            $content = "Inizio: <b>" . $start  . "</b><br> Fine: <b>" . $end . "</b>";

            $title = "";
            $allDay = false;
            if ($hour->order !== null){
                $title = 'Commessa - ';
            }
            if ($hour->report !== null){
                $title = 'FI - ';
            }
            if ($hour->description !== null){
                $title = 'Altro - ';
            }if ($hour->holiday !== null){
                $allDay = Holiday::where('id',$hour->holiday)->pluck('allDay');
                $title = 'Ferie - ';
            }

            $formatted[] = [
              'title' => $title . User::where('id',$hour->user)->pluck('name'),
              'start' => $hour->start,
              'end' => $hour->end,
              'allDay' => $allDay,
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
    public function edit(): void
    {}
    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $data = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);
        unset($data['_token'], $data['fi'], $data['night']);
        Hour::create(array_merge($data,$request->except(['_token','fi','night'])));

        return redirect('/ore')->with('message', 'Ore inserite con successo');
    }
    public function update(): void
    {}
    public function destroy(): void
    {}
}
