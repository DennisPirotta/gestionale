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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class HourController extends Controller
{
    public function index()
    {
        $parameters = [
          'user' => request('user') ?? auth()->id(),
          'mese' => request('mese')
        ];
        $refresh = false;
        if(!request()->has('user') || ( request()->has('user') && request('user') === null )) {
            $parameters['user'] = auth()->id();
            $refresh = true;
        }
        if(!request()->has('mese') || ( request()->has('mese') && request('mese') === null )) {
            $parameters['mese'] = Carbon::now()->format('Y-m');
            $refresh = true;
        }
        if ($refresh){
            return redirect()->route('hours.index',$parameters);
        }
        return view('hours.index', [
            'users' => User::with('hours')->get(),
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'original_orders' => Order::with(['status', 'customer'])->orderBy('status_id')->get(),
            'customers' => Customer::all(),
            'technical_reports' => TechnicalReport::with(['customer', 'secondary_customer'])->orderBy('customer_id')->get(),
        ]);
    }

    public function edit(): void
    {
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $default = $request->validate([
            'count' => 'required',
            'hour_type_id' => 'required',
            'day_start' => 'required',
        ]);
        $user = auth()->id();
        if (isset($request['user_id'])) {
            $user = (int)$request['user_id'];
        }

        $end = $request['day_end'];

        if ($end !== null){
            $period = CarbonPeriod::create($request['day_start'], $request['day_end']);
            $period->setEndDate($period->getEndDate()->modify('-1 day'));
        }else{
            $period = CarbonPeriod::create($request['day_start'], $request['day_start']);
            $end = $request['day_start'];
        }

        $message = '';

        $multiple = false;

        foreach ($period as $day) {
            if (!Carbon::isOpenOn($day->format('Y-m-d'))) {
                continue;
            }
            $hour = Hour::create([
                'count' => str_replace(',', '.', $default['count']),
                'date' => $day,
                'user_id' => $user,
                'hour_type_id' => $default['hour_type_id']
            ]);
            switch ($request['hour_type_id']) {
                case '1':
                {   // Commessa
                    $data = $request->validate([
                        'job_type_id' => 'required',
                        'order_id' => 'required'
                    ]);
                    OrderDetails::create(array_merge($data, [
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
                case '2':
                {   // FI
                    $data = $request->validate([
                        'number' => 'nullable',
                        'fi_number' => 'nullable',
                        'secondary_customer_id' => 'nullable',
                        'customer_id' => 'nullable',
                        'order_id' => 'nullable',
                        'user_id' => 'nullable'
                    ]);
                    if (!isset($data['user_id'])){
                        $data['user_id'] = $user;
                    }
                    if (!$multiple) {
                        if ($request->fi_new === '0') {
                            TechnicalReport::create($data);
                        }
                        $message = 'Ore foglio intervento inserite con successo';
                        $multiple = true;
                    }
                    if ($request['night'] === 'XUE') {
                        $xue = true;
                    }
                    if ($request['night'] === 'UE') {
                        $ue = true;
                    }

                    if ($data['number'] === null) {
                        $fi = TechnicalReport::find($data['fi_number']);
                    } else {
                        $fi = TechnicalReport::where('number', $data['number'])->first();
                    }
                    TechnicalReportDetails::create([
                        'hour_id' => $hour->id,
                        'technical_report_id' => $fi->id,
                        'nightEU' => $ue ?? false,
                        'nightExtraEU' => $xue ?? false
                    ]);
                    break;
                }
                case '3':
                {   // Assistenza ??
                    $message = 'Ore di assistenza inserite con successo';
                    break;
                }
                case '4':
                {   // AVIS
                    $message = 'Ore di AVIS inserite con successo';
                    break;
                }
                case '5':
                {   // Corso
                    $message = 'Ore di corso inserite con successo';
                    break;
                }
                case '6':
                {   // Ferie
                    if (!$multiple) {
                        $holiday = Holiday::create([
                            'approved' => true,
                            'start' => $request['day_start'],
                            'end' => $end,
                            'user_id' => auth()->id()
                        ]);
                        $holiday->sendMail();
                        $message = 'Ore di ferie inserite con successo';
                        $multiple = true;
                    }

                    break;
                }
                case '7':
                {   // Malattia
                    $message = 'Ore di malattia inserite con successo';
                    break;
                }
                case '8':
                {   // Ufficio
                    $data = $request->validate([
                        'description' => 'required'
                    ]);
                    $hour->update([
                        'description' => $data['description']
                    ]);
                    $message = 'Ore di ufficio inserite con successo';
                    break;
                }
                case '9':
                {   // Visita Medica
                    $message = 'Ore visita medica inserite con successo';
                    break;
                }
                case '10':
                {  // Altro
                    $data = $request->validate([
                        'description' => 'required'
                    ]);
                    $hour->update([
                        'description' => $data['description']
                    ]);
                    $message = 'Ore inserite con successo';
                    break;
                }
                default:
                {
                    break;
                }
            }
        }

        return redirect('/ore')->with('message', $message);
    }

    public function create(): Application|View|Factory
    {
        return view('hours.create', [
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'customers' => Customer::all(),
            'orders' => Order::all()->sortBy('status'),
            'statuses' => Status::all()
        ]);
    }

    public function update(): void
    {
    }

    public function destroy(Hour $hour): RedirectResponse
    {
        $hour->delete();
        return back()->with('message', 'Ora eliminata con successo');
    }

    public function report(): Factory|View|Application
    {
        return view('hours.report', [
            'users' => User::with('hours')->get(),
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'orders' => Order::with(['status', 'customer'])->orderBy('status_id')->get(),
            'customers' => Customer::all(),
            'technical_reports' => TechnicalReport::with(['customer', 'secondary_customer'])->orderBy('customer_id')->get(),
        ]);
    }
}
