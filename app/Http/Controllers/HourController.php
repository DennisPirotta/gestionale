<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHourRequest;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $data = Hour::with('hour_type')->filter(request(['month', 'user']))->get();
        $user = User::find(request('user', auth()->id()));

        $technical_report_hours = TechnicalReportDetails::with(['technical_report','technical_report.customer','hour'])->whereIn('hour_id', $data->where('hour_type_id', 2)->map(function ($item) {
            return $item->id;
        }))->get();
        $order_hours = OrderDetails::with(['order','order.customer','hour'])->whereIn('hour_id', $data->where('hour_type_id', 1)->map(function ($item) {
            return $item->id;
        }))->get();

        return response()->view('hours.index', [
            'user' => $user,
            'technical_report_hours' => $technical_report_hours->groupBy('technical_report_id'),
            'order_hours' => $order_hours->groupBy('order_id'),
            'other_hours' => $data->whereNotIn('hour_type_id', [1,2])->groupBy('hour_type_id'),
            'period' => CarbonPeriod::create(Carbon::parse(request('month'))->firstOfMonth(), Carbon::parse(request('month'))->lastOfMonth()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('hours.create', [
            'hour_types' => HourType::all(),
            'orders' => Order::with('customer', 'status')->orderByDesc('innerCode')->get(),
            'job_types' => JobType::all(),
            'technical_reports' => TechnicalReport::with('customer', 'secondary_customer')->get(),
            'customers' => Customer::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreHourRequest $request
     * @return RedirectResponse|Hour
     */
    public function store(StoreHourRequest $request)
    {
        if ($request->has('date')) {
            $validated = $request->validated();
            if (!array_key_exists('user_id', $validated)) {
                $validated['user_id'] = request('user', auth()->id());
            }
            $hour = Hour::create($validated);
            if ($request->ajax()) {
                return $hour;
            }
            if ($hour->hour_type->id === 1) {
                $this->storeOrderDetails($hour, $request);
            } elseif ($hour->hour_type->id === 2) {
                $this->storeTechnicalReportDetails($hour, $request);
            }
        } else {
            $this->multipleStore($request);
        }
        return redirect()->action([__CLASS__, 'index'], ['month' => Carbon::parse($validated['date'] ?? $validated['start'] ?? 'now')->format('Y-m'), 'user' => $validated['user_id'] ?? request('user', auth()->id())])->with('message', 'Ora Inserita Correttamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  Hour  $hour
     * @return void
     */
    public function show(Hour $hour): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Hour  $hour
     * @return void
     */
    public function edit(Hour $hour): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Hour  $hour
     * @return RedirectResponse
     */
    public function destroy(Hour $hour): RedirectResponse
    {
        if ($hour->order_hour()) {
            $hour->order_hour()->delete();
        }
        if ($hour->technical_report_hour()) {
            $hour->technical_report_hour()->delete();
        }
        $hour->delete();

        return back()->with('message', 'Ora eliminata con successo');
    }

    /**
     * @throws ValidationException
     */
    public function storeOrderDetails(Hour $hour, StoreHourRequest $request): void
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
    public function storeTechnicalReportDetails(Hour $hour, StoreHourRequest $request): void
    {
        $details = Validator::make($request->only(['extra', 'night']), [
            'extra' => 'required',
            'night' => 'required',
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
            'nightEU' => $info['night'] === 'eu',
            'nightExtraEU' => $info['night'] === 'xeu',
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
                $this->storeOrderDetails($hour, $request);
            } elseif ($hour->hour_type->id === 2) {
                $this->storeTechnicalReportDetails($hour, $request);
            }
        }
    }

    public function print(): Response
    {
        $data = Hour::with('type')->filter(request(['month', 'user']))->get();
        $user = User::find(request('user', auth()->id()));

        $technical_report_hours = TechnicalReportDetails::with(['technical_report','technical_report.customer','hour'])->whereIn('hour_id', $data->where('hour_type_id', 2)->map(function ($item) {
            return $item->id;
        }))->get();
        $order_hours = OrderDetails::with(['order','order.customer','hour'])->whereIn('hour_id', $data->where('hour_type_id', 1)->map(function ($item) {
            return $item->id;
        }))->get();

        return response()->view('hours.partial.print', [
            'user' => $user,
            'technical_report_hours' => $technical_report_hours->groupBy('technical_report_id'),
            'order_hours' => $order_hours->groupBy('order_id'),
            'other_hours' => $data->whereNotIn('hour_type_id', [1,2])->groupBy('hour_type_id'),
            'period' => CarbonPeriod::create(Carbon::parse(request('month'))->firstOfMonth(), Carbon::parse(request('month'))->lastOfMonth()),
        ]);
    }

    public function update(Request $request, Hour $hour)
    {
        $request->validate([
            'count' => ['required','numeric']
        ]);
        $hour->update([
            'count' => $request->get('count')
        ]);
    }
}
