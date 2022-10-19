<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ExpenseReport;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('expense-report.index',[
            'users' => User::with('expense_reports','expense_reports.customer')->get(),
            'customers' => Customer::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'customer_id' => 'required',
            'location' => 'required',
            'km' => 'required',
            'food' => 'required',
            'various' => 'required',
            'transport' => 'required',
        ]);

        $data['note'] = $request['note'] ?? null;
        $data['user_id'] = auth()->id();

        ExpenseReport::create($data);

        return back()->with('message','Nota spesa inserita correttamente');
    }
}
