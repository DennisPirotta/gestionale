<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Status;
use App\Models\User;
use Cassandra\Custom;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class OrderController extends Controller
{
    // Visualizza tutte le commesse
    public function index(): Factory|View|Application
    {
        $commesse = Order::with(['user','status','company','customer']);
        if (request('customer')) {
            $commesse = $commesse->filter(request(['customer']));
        } elseif (request('search')) {
            $commesse = $commesse->filter(request(['search']));
        } elseif (request('company')) {
            $commesse = $commesse->filter(request(['company']));
        }

        return view('orders.index', [
            'commesse' => $commesse->get(),
            'statuses' => Status::all()
        ]);
    }

    // Filtra per commessa
    public function show(Order $order): Factory|View|Application
    {
        return view('orders.show', [
            'commessa' => $order->load(['technical_reports.customer','technical_reports.secondary_customer','technical_reports.user','order_details.hour','order_details.job_type'])
        ]);
    }

    // Mostra pagina per creare una nuova commessa

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $formFields = $request->validate([
            'company_id' => 'required',
            'status_id' => 'required',
            'country_id' => 'required',
            'description' => 'required',
            'hourSW' => 'required',
            'job_type_id' => 'required',
            'opening' => 'required',
            'customer_id' => 'required',
        ]);

        $formFields['innerCode'] = (Order::orderBy('innerCode','desc')->first()->innerCode) + 1;
        $formFields['outerCode'] = (Order::orderBy('outerCode','desc')->first()->innerCode) + 1;

        $formFields['user_id'] = auth()->id();

        Order::create($formFields);

        return redirect('/commesse')->with('message', 'Commessa inserita con successo');
    }

    // Salva la nuova commessa

    public function create(): Factory|View|Application
    {
        return view('orders.create', [
            'customers' => Customer::all(),
            'countries' => Country::all(),
            'companies' => Company::all(),
            'statuses' => Status::all(),
            'hour_types' => HourType::all(),
            'job_types' => JobType::all(),
            'orders' => Order::all()
        ]);
    }

    // Mostra pagina per modificare una commessa

    public function edit(Order $order): Factory|View|Application
    {
        return view('orders.edit', [
            'commessa' => $order,
            'companies' => Company::all(),
            'statuses' => Status::all(),
            'countries' => Country::all(),
            'customers' => Customer::all()
        ]);
    }

    // Modifica la commessa
    public function update(Request $request, Order $order): Redirector|Application|RedirectResponse
    {
        $formFields = $request->validate([
            'company' => 'required',
            'status' => 'required',
            'country' => 'required',
            'description' => 'required',
            'hourSW' => 'required',
            'progress' => 'required',
            'opening' => 'required',
            'closing' => 'required',
            'customer' => 'required',
        ]);

        $formFields['innerCode'] = $order->innerCode;
        $formFields['outerCode'] = $order->outerCode;

        $formFields['manager'] = $order->manager;

        $order->update($formFields);

        return redirect('/commesse')->with('message', 'Commessa aggiornata con successo');
    }

    // Elimina la commessa
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return back()->with('message', 'Commessa eliminata con successo');
    }

    // Report commesse
    public function report()
    {
        return view('orders.report',[
            'orders' => Order::with(['job_type','status','country','company','user','customer'])->orderBy('status_id')->get(),
            'order_details' => OrderDetails::with(['hour','order'])->get()
        ]);
    }
}
