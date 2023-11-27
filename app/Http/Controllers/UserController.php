<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\BusinessHour;
use App\Models\Customer;
use App\Models\Hour;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\TechnicalReport;
use App\Models\TechnicalReportDetails;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    // Visualizza tutti i dipendenti
    public function index(Request $request): Factory|View|Application
    {
        $users = User::with(['company','hours','orders'])->get();

        return view('users.index', [
            'users' => $users,
            'invoices' => $this->getInvoices($users)
        ]);
    }

    // Filtra per commessa
    public function show(User $user): Factory|View|Application
    {
        return view('users.show', [
            'user' => $user->load(['company', 'business_hours']),
        ]);
    }

    // Mostra pagina per modificare una commessa

    public function edit(User $user)
    {
        //
    }

    // Modifica la commessa
    public function update(Request $request, User $user): void
    {
        //
    }

    // Elimina l'utente
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return back()->with('message', 'Utente eliminato con successo');
    }

    // Mostra ore settimanali utente
    public function updateBusinessHour(Request $request, User $user): RedirectResponse
    {
        //dd($request, $user->business_hours);
        foreach ($user->business_hours as $business_hour) {
            $business_hour->update([
                'morning_start' => $request['days'][$business_hour->week_day]['morning_start'],
                'morning_end' => $request['days'][$business_hour->week_day]['morning_end'],
                'afternoon_start' => $request['days'][$business_hour->week_day]['afternoon_start'],
                'afternoon_end' => $request['days'][$business_hour->week_day]['afternoon_end'],
            ]);
        }
        Carbon::setOpeningHours(BusinessHour::getWorkingHours($user));

        return back()->with('message', 'Orario di lavoro modificato correttamente');
    }

    // Mostra ore settimanali utente
    public function updateHolidaysHour(Request $request, User $user): RedirectResponse
    {
        $user->holidays = $request['holidays'];
        $user->save();

        return back()->with('message', 'Ore di ferie modificate correttamente');
    }

    public function updatePermissions(Request $request, User $user)
    {
        $roles = [];

        if (isset($request['user'])) {
            $roles[] = 'user';
        }

        if (isset($request['admin'])) {
            $roles[] = 'admin';
        }
        if (isset($request['developer'])) {
            $roles[] = 'developer';
        }
        if (isset($request['boss'])) {
            $roles[] = 'boss';
        }

        $user->syncRoles();
        foreach ($roles as $role) {
            $user->assignRole($role);
        }

        return back()->with('message', 'Permessi cambiati con successo');
    }

    public function resigned(User $user)
    {
        $user->update([ 'hired' => false ]);
        return back()->with('message', 'Utente contrassegnato come dimesso con successo');
    }

    private function getInvoices($users)
    {
        $invoices = [];
        foreach ($users as $user) {

            if (request()->has('from') && request()->has('to')) {



                $from = Carbon::parse(request('from'));
                $to = Carbon::parse(request('to'));

                $total = $user->hours->whereBetween('date',[$from, $to])->sum('count');
                $details = OrderDetails::with('order.customer')
                    ->whereHas('hour', fn($query) => $query
                        ->where('user_id',$user->id)
                        ->whereBetween('date', [$from,$to]))
                    ->get();

                $order_details = OrderDetails::with('order.customer')
                    ->whereHas('hour', fn($query) => $query
                        ->where('user_id',$user->id)
                        ->whereBetween('date', [$from,$to])
                    )
                    ->get();

                $technical_report_details = TechnicalReportDetails::with('technical_report.customer')
                    ->whereHas('hour', fn($query) => $query
                        ->where('user_id',$user->id)
                        ->whereBetween('date', [$from,$to])
                    )
                    ->get();






            }else{
                $details = OrderDetails::with('order.customer')
                    ->whereHas('hour', fn($query) => $query->where('user_id',$user->id))->get();

                $order_details = OrderDetails::with('order.customer')
                    ->whereHas('hour', fn($query) => $query->where('user_id',$user->id))->get();

                $technical_report_details = TechnicalReportDetails::with('technical_report.customer')
                    ->whereHas('hour', fn($query) => $query->where('user_id',$user->id))->get();

                $total = $user->hours->sum('count');
            }


            $detailsByCustomer = [];

            foreach ($order_details as $detail) {
                $customerName = $detail->order->customer->name;

                if (!array_key_exists($customerName, $detailsByCustomer)) {
                    $detailsByCustomer[$customerName] = 0;
                }

                $detailsByCustomer[$customerName] += $detail->hour->count;

            }

            foreach ($technical_report_details as $detail) {
                $customerName = $detail->technical_report->customer->name;

                if (!array_key_exists($customerName, $detailsByCustomer)) {
                    $detailsByCustomer[$customerName] = 0;
                }

                $detailsByCustomer[$customerName] += $detail->hour->count;

            }

            $invoices[] = [
                "name" => $user->name . " " . $user->surname,
                "total" => $total,
                "customers" => $detailsByCustomer
            ];
        }
        return $invoices;
    }

    public function exportInvoices()
    {
        return Excel::download(new InvoicesExport($this->getInvoices(User::with(['company','hours','orders'])->get())), 'users.xlsx');
    }
}
