<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Holiday;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Status;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;

class HourController extends Controller
{
    public function index(): Factory|View|Application
    {

        $hours = Hour::all();
        $users = User::all();
        $holidays = Holiday::all();

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
                $allDay = $holidays->where('id',$hour->holiday)->pluck('allDay');
                $title = 'Ferie - ';
            }

            $formatted[] = [
              'title' => $title . $users->where('id',$hour->user)->value('name'),
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

        $data = Validator::make($request->only(['start','end','hour_type']),[
            'start' => 'required',
            'end' => 'required',
            'hour_type' => 'required'
        ]);

        try {
            $start = new DateTime($data->getData()['start']);
            $end = new DateTime($data->getData()['end']);
        }catch (Exception $e){
            return response($e,500);
        }

        $hour = Hour::create([
            'start' => $start,
            'end' => $end,
            'user' => auth()->user()['id'],
            'hour_type' => $data->getData()['hour_type']
        ]);
        // se stai leggendo scusa per questo

        switch ($data->getData()['hour_type']){
            case 1: {
                // commessa
                OrderDetails::create([
                    'order' => $request->get('order'),
                    'hour' => $hour->id,
                    'hourSW' => $request['hourSW'],
                    'hourMS' => $request['hourMS'],
                    'hourFAT' => $request['hourFAT'],
                    'hourSAF' => $request['hourSAF'],
                ]);
            }
            case 2: {
                // fi

            }
            case 6: {
                // ferie

            }
            default: {
                // Altro

            }
        }





        return redirect('/ore')->with('message', 'Ore inserite con successo');
    }
    public function update(): void
    {}
    public function destroy(): void
    {}
}
