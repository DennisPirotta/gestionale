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
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
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
        $hours = Hour::with(['user','hour_type'])->get();

        $formatted = [];
        foreach ($hours as $hour){
            $content = '<form method="POST" action="'.route('hours.destroy',$hour->id).'">'.csrf_field().method_field('DELETE').'<button class="btn btn-outline-danger" onclick="return confirm("Sicuro di voler Eliminare?")"><i class="bi bi-trash me-1 fs-4"></i></button></form>';
            $formatted[] = [
              'title' => $hour->user->name . " " . $hour->user->surname . " - " . $hour->hour_type->description,
              'start' => $hour->date,
              'end' => $hour->date,
              'allDay' => true,
              'extendedProps' => [
                  'content' => $content,
                  'hour_type' => $hour->hour_type->description,
                  'name' => $hour->user->name . " " . $hour->user->surname,
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
            'count' => 'required',
            'hour_type_id' => 'required'
        ]);
        $message = '';
        $period = CarbonPeriod::create($request['day_start'],$request['day_end']);
        $period->setEndDate($period->getEndDate()->modify('-1 day'));

        $multiple = false;

        foreach ($period as $day){
            if (!Carbon::isOpenOn($day->format('Y-m-d'))) {
                continue;
            }
            $hour = Hour::create([
                'count' => $default['count'],
                'date' => $day,
                'user_id' => auth()->id(),
                'hour_type_id' => $default['hour_type_id']
            ]);
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
                    Order::find($data['order_id'])->update([
                        'job_type_id' => $data['job_type_id']
                    ]);
                    $message = 'Ore commessa inserite con successo';
                    break;
                }
                case '2': {   // FI
                    $data = $request->validate(['number' => 'required']);
                    if (!$multiple){
                        if ($request->fi_new === '0'){
                            TechnicalReport::create([
                                'user_id' => auth()->id(),
                                'number' => $data['number'],
                                'secondary_customer_id' => $request['secondary_customer_id'] ?? null,
                                'order_id' => $request['fi_order_id'] ?? null,
                                'customer_id' => $request['customer_id']
                            ]);
                        }
                        $message = 'Ore foglio intervento inserite con successo';
                        $multiple = true;
                    }
                    if ($request['night'] === 'XUE'){
                        $xue = true;
                    }
                    if ($request['night'] === 'UE'){
                        $ue = true;
                    }
                    $fi = TechnicalReportDetails::create([
                        'hour_id' => $hour->id,
                        'technical_report_id' => TechnicalReport::where('number',$data['number'])->get()[0]->id,
                        'nightEU' => $ue ?? false,
                        'nightExtraEU' => $xue ?? false
                    ]);
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
                    if(!$multiple){
                        Holiday::create([
                            'approved' => true,
                            'start' => $request['day_start'],
                            'end' => $request['day_end'],
                            'user_id' => auth()->id()
                        ]);
                        $message = 'Ore di ferie inserite con successo';
                        $multiple = true;
                    }

                    break;
                }
                case '7': {   // Malattia
                    $message = 'Ore di malattia inserite con successo';
                    break;
                }
                case '8': {   // Ufficio
                    $data = $request->validate([
                        'description' => 'required'
                    ]);
                    $hour->update([
                        'description' => $data['description']
                    ]);
                    $message = 'Ore di ufficio inserite con successo';
                    break;
                }
                case '9': {   // Visita Medica
                    $message = 'Ore visita medica inserite con successo';
                    break;
                }
                case '10': {  // Altro
                    $data = $request->validate([
                        'description' => 'required'
                    ]);
                    $hour->update([
                        'description' => $data['description']
                    ]);
                    $message = 'Ore inserite con successo';
                    break;
                }
                default:{
                    break;
                }
            }
        }

        return redirect('/ore')->with('message', $message);
    }
    public function update(): void
    {}
    public function destroy(Hour $hour): RedirectResponse
    {
        $hour->delete();
        return back()->with('message','Ora eliminata con successo');
    }

    public function report(): Factory|View|Application
    {

        return view('hours.report',[
            'users' => User::with('hours')->get()
        ]);
    }
}
