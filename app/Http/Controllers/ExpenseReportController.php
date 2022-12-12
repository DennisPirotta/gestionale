<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ExpenseReport;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpenseReportController extends Controller
{
    public function index(): Application|Factory|View|RedirectResponse
    {
        $user = User::find(request('user', auth()->id()));
        $month = Carbon::parse(request('month', 'now'));
        $period = CarbonPeriod::create(clone $month->firstOfMonth(), $month->lastOfMonth());
        $reports = $user->expense_reports->filter(static function ($item) use ($period) {
            return Carbon::parse($item->date)->isBetween($period->first(), $period->last());
        })->load('customer');
        if (auth()->id() != request('user') && !auth()->user()->hasRole('admin|boss')) {
            return redirect()->action([__CLASS__, 'index'], ['month' => $month->format('Y-m'), 'user' => auth()->id()]);
        }
        return view('expense-report.index', [
            'users' => User::with('expense_reports', 'expense_reports.customer')->get(),
            'customers' => Customer::all(),
            'user' => $user,
            'month' => $month,
            'period' => $period,
            'reports' => $reports
        ]);
    }

    public function store(Request $request): RedirectResponse
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

        $data['note'] = $request->get('note');
        $data['user_id'] = $request->get('user', auth()->id());

        ExpenseReport::create($data);

        return back()->with('message', 'Nota spesa inserita correttamente');
    }
}
