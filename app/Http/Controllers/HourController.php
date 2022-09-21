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
use App\Models\TechnicalReport;
use App\Models\TechnicalReportDetails;
use Carbon\Carbon;
use DateTime;
use Exception;
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

        $holidays = Holiday::all();
        $hours = Hour::with(['user'])->get();

        $formatted = [];
        foreach ($hours as $hour){
            $start = null;
            $end = null;
            try {
                $start = new DateTime($hour->start);
                $end = new DateTime($hour->end);
            } catch (Exception) { }
            $content = "Inizio: <b>" . $start->format('H:i')  . "</b><br> Fine: <b>" . $end->format('H:i') . "</b>";

            $formatted[] = [
              'title' => $hour->user->name . " " . $hour->user->surname,
              'start' => $hour->start,
              'end' => $hour->end,
              'allDay' => $holidays->where('hour_id',$hour->id)->value('allDay'),
              'extendedProps' => [
                  'content' => $content
              ]
            ];
        }

        return view('hours.index',[
            'hours' => $formatted,
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'orders' => Order::with(['status','customer'])->orderBy('status_id')->get(),
            'customers' => Customer::all(),
            'technical_reports' => TechnicalReport::with(['customer','secondary_customer'])->orderBy('customer_id')->get()
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
        $default = $request->validate([
            'start' => 'required',
            'end' => 'required',
            'hour_type_id' => 'required'
        ]);

        $default['start'] = DateTime::createFromFormat('Y-m-d H:i',$request['day_start'] . " " . $default['start']);
        $default['end'] = DateTime::createFromFormat('Y-m-d H:i',$request['day_end'] . " " . $default['end'])->modify('-1 day');

        if ($default['start']->format('Y-m-d') !== $default['end']->format('Y-m-d')) {
            return back()->with('error', "L'inserimento multiplo non è ancora disponibile per questa sezione");
        }

        $default['user_id'] = auth()->id();


        $used = 0;
        $hours = Hour::where('user_id',auth()->id())->get();
        foreach ($hours as $hour){
            if (Carbon::parse($hour->start)->isSameDay(Carbon::parse($default['start']))) {
                $used += Carbon::parse($hour->start)->diffInBusinessHours($hour->end);
            }
        }

        if ($used >= 8 ) {
            return back()->with('error', 'hai già inserito il numero massimo di ore per oggi');
        }

        $hour = Hour::create($default);

        $message = '';

        switch ($request['hour_type_id']){
            case '1': {   // Commessa
                $data = $request->validate([
                    'job_type_id' => 'required',
                    'order_id' => 'required'
                ]);
                OrderDetails::create(array_merge($data,[
                    'hour_id' => $hour->id,
                    'description' => $request->job_description ?? null,
                    'signed' => isset($request->signed) ? (bool)$request->signed : null
                ]));
                $message = 'Ore commessa inserite con successo';
                break;
            }
            case '2': {   // FI
                $data = $request->validate(['number' => 'required']);
                if ($request->fi_new === '0'){
                    TechnicalReport::create(array_merge($data,[
                        'secondary_customer_id' => $request['secondary_customer_id'] ?? null,
                        'order_id' => $request['fi_order_id'] ?? null,
                        'customer_id' => $request['customer_id']
                    ]));
                }
                TechnicalReportDetails::create([
                    'hour_id' => $hour->id,
                    'technical_report_id' => TechnicalReport::where('number',$data['number'])->get()[0]->id
                ]);
                $message = 'Ore foglio intervento inserite con successo';
                break;
            }
            case '3': {   // Assistenza ??
                $message = 'Ore di assistenza inserite con successo';
                break;
            }
            case '4': {   // AVIS
                $message = 'Ore di AVIS inserite con successo';
                break;
            }
            case '5': {   // Corso
                $message = 'Ore di corso inserite con successo';
                break;
            }
            case '6': {   // Ferie
                $message = 'Ore di ferie inserite con successo';
                break;
            }
            case '7': {   // Malattia
                $message = 'Ore di malattia inserite con successo';
                break;
            }
            case '8': {   // Ufficio
                $message = 'Ore di ufficio inserite con successo';
                break;
            }
            case '9': {   // Visita Medica
                $message = 'Ore visita medica inserite con successo';
                break;
            }
            case '10': {  // Altro
                $message = 'Ore inserite con successo';
                break;
            }
            default:{

            }
        }
        return redirect('/ore')->with('message', $message);
    }
    public function update(): void
    {}
    public function destroy(): void
    {}
}
