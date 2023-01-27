<?php

namespace App\Http\Controllers;

use App\Mail\BugReportMail;
use App\Models\BugReport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BugReportController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'object' => 'required',
            'description' => 'required',
        ]);
        $this->store($data);
        Mail::to('dennispirotta@gmail.com')->send(new BugReportMail($data['object'], $data['description'], auth()->user()->email));

        return back()->with('message', "L'errore è stato segnalato");
    }

    public function index(): Factory|View|Application
    {
        return view('debug.index', [
            'bugs' => BugReport::with('reporter')->get(),
        ]);
    }

    public function store($data)
    {
        $data['reported_by'] = auth()->user()->id;
        BugReport::create($data);

        return back()->with('message', 'Report aggiornato con successo');
    }

    public function update(BugReport $bugReport)
    {
        $bugReport->update([
            'fixed' => true,
        ]);
        return back()->with('message', 'Report aggiornato con successo');
    }

    public function delete()
    {
    }
}
