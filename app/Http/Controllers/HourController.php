<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHourRequest;
use App\Http\Requests\UpdateHourRequest;
use App\Models\Customer;
use App\Models\Hour;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\TechnicalReport;
use App\Models\TechnicalReportDetails;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HourController extends Controller
{
    public function index(): Response
    {
        return response()->view('hours.index', [
            'data' => Hour::with('hour_type')->filter(request(['month', 'user']))->get()->groupBy(
                [
                    static function ($item) {
                        return $item->hour_type->description;
                    },
                    static function ($item) {
                        if ($item->hour_type->description === 'Commessa') {
                            return $item->order_hour()->order->innerCode;
                        }
                        if ($item->hour_type->description === 'Foglio intervento') {
                            return $item->technical_report_hour()->technical_report->number;
                        }
                        return false;
                    }, static function ($item) {
                        if ($item->hour_type->description === 'Commessa') {
                            return $item->order_hour()->job_type->title;
                        }
                        return false;
                    },
                ]
            ),
            'period' => CarbonPeriod::create(Carbon::parse(request('month'))->firstOfMonth(), Carbon::parse(request('month'))->lastOfMonth()),
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'original_orders' => Order::with(['status', 'customer'])->orderBy('status_id')->get(),
            'users' => User::all(),
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(StoreHourRequest $request): Redirector|Application|RedirectResponse|Response
    {
        $validated = $request->validated();
        if (! isset($validated['date'])) {
            $this->multipleStore($request);
        } else {
            $validated['user_id'] = $request->get('user_id', auth()->id());
            $hour = Hour::create($validated);
            if ($hour->hour_type->id === 1) {
                $this->storeOrderHour($hour, $request);
            } elseif ($hour->hour_type->id === 2) {
                $this->storeTechnicalReportHour($hour, $request);
            }
        }
        if ($request->ajax()) {
            return response('Ora Inserita Correttamente');
        }

        return redirect()->action([__CLASS__, 'index'], ['month' => Carbon::now()->format('Y-m'), 'user' => $request->get('user_id', auth()->id())])->with('message', 'Ora Inserita Correttamente');
    }

    public function create(): Response
    {
        return response()->view('hours.create', [
            'hour_types' => HourType::all(),
            'orders' => Order::with('customer', 'status')->orderBy('status_id')->get(),
            'job_types' => JobType::all(),
            'technical_reports' => TechnicalReport::with('customer', 'secondary_customer')->get(),
            'customers' => Customer::all(),
        ]);
    }

    public function update(UpdateHourRequest $request, Hour $hour): Response|RedirectResponse|Application|ResponseFactory
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->get('user_id', auth()->id());
        $hour->update($validated);
        if ($hour->hour_type->id === 1) {
            $details = Validator::make($request->only(['extra', 'job', 'signed']), [
                'extra' => 'required',
                'job' => 'required',
                'signed' => 'nullable',
            ]);
            $hour->order_hour()->update($details->validated());
        } elseif ($hour->hour_type->id === 2) {
            $details = Validator::make($request->only(['extra', 'night']), [
                'extra' => 'required',
                'night' => 'required',
            ]);
            $hour->technical_report_hour()->update($details->validated());
        }
        if ($request->ajax()) {
            return response('Ora Modificata Correttamente');
        }

        return redirect()->route('hours.index')->with('message', 'Ora Modificata Correttamente');
    }

    public function destroy(Hour $hour): RedirectResponse
    {
        if (OrderDetails::where('hour_id', $hour->id)->exists()) {
            OrderDetails::where('hour_id', $hour->id)->delete();
        }
        if (TechnicalReportDetails::where('hour_id', $hour->id)->exists()) {
            TechnicalReportDetails::where('hour_id', $hour->id)->delete();
        }
        $hour->delete();

        return redirect()->route('hours.index')->with('message', 'Ora eliminata con successo');
    }

    public function storeOrderHour(Hour $hour, StoreHourRequest $request): void
    {
        $details = Validator::make($request->only(['extra', 'job', 'signed']), [
            'extra' => 'required',
            'job' => 'required',
            'signed' => 'nullable',
        ]);
        if ($details->fails()) {
            Session::flash('hour_type', $hour->type->id);
            $hour->delete();
        }
        $info = $details->validated();
        $order = Order::find($info['extra']) ?? Order::where('innerCode', $info['extra'])->first();
        OrderDetails::create([
            'hour_id' => $hour->id,
            'signed' => $info['signed'] ?? false,
            'order_id' => $order->id,
            'job_type_id' => $info['job'],
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function storeTechnicalReportHour(Hour $hour, StoreHourRequest $request): void
    {
        $details = Validator::make($request->only(['extra', 'night']), [
            'extra' => 'required',
            'night' => 'nullable',
        ]);
        if ($details->fails()) {
            Session::flash('hour_type', $hour->hour_type_id);
            $hour->delete();
        }
        $info = $details->validated();
        if ($info['extra'] === 'new') {
            $technical_report = $this->storeTechnicalReport($request);
        } else {
            $technical_report = TechnicalReport::find($info['extra']) ?? TechnicalReport::where('number', $info['extra'])->first();
        }
        TechnicalReportDetails::create([
            'hour_id' => $hour->id,
            'nightEU' => isset($info['night']) && $info['night'] === 'eu',
            'nightExtraEU' => isset($info['night']) && $info['night'] === 'xeu',
            'technical_report_id' => $technical_report->id,
        ]);
    }

    /**
     * @throws ValidationException
     */
    private function storeTechnicalReport(StoreHourRequest $request): Model|TechnicalReport
    {
        $validated = Validator::make($request->only(['number', 'fi_order_id', 'customer_id', 'secondary_customer_id']), [
            'number' => 'required',
            'fi_order_id' => 'nullable',
            'customer_id' => 'required',
            'secondary_customer_id' => 'nullable',
        ])->validated();
        $validated['user_id'] = $request->get('user_id', auth()->id());

        return TechnicalReport::where('number', $validated['number'])->exists() ? TechnicalReport::where('number', $validated['number'])->first() : TechnicalReport::create($validated);
    }

    /**
     * @throws ValidationException
     */
    private function multipleStore(StoreHourRequest $request): void
    {
        $validated = $request->validated();
        $period = CarbonPeriod::create($validated['start'], $validated['end']);
        foreach ($period as $day) {
            $hour = Hour::create([
                'count' => $validated['count'],
                'date' => $day->format('Y-m-d'),
                'hour_type_id' => $validated['hour_type_id'],
                'description' => $validated['description'],
                'user_id' => $request->get('user_id', auth()->id()),
            ]);
            if ($hour->hour_type->id === 1) {
                $this->storeOrderHour($hour, $request);
            } elseif ($hour->hour_type->id === 2) {
                $this->storeTechnicalReportHour($hour, $request);
            }
        }
    }

    public function updateNightFI(Hour $hour)
    {
        $fi = TechnicalReportDetails::where('hour_id', $hour->id)->first();
        if (!$fi->nightEU && !$fi->nightExtraEU) {
            $fi->nightEU = true;
        } elseif ($fi->nightEU) {
            $fi->nightEU = false;
            $fi->nightExtraEU = true;
        } else {
            $fi->nightExtraEU = false;
            $fi->nightEU = false;
        }
        $fi->save();
    }
}
