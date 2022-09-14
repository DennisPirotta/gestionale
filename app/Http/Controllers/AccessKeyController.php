<?php

namespace App\Http\Controllers;

use App\Models\AccessKey;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Crypt;

class AccessKeyController extends Controller
{
    public function index(): Factory|View|Application
    {
        $keys = AccessKey::all();
        return view('access.index', [
            'keys' => $keys
        ]);
    }

    public function store(Request $request): Redirector|Application|RedirectResponse
    {
        $formFields = $request->validate([
            'key' => 'required'
        ]);
        AccessKey::create([
            'key' => Crypt::encryptString($formFields['key'])
        ]);
        return redirect('/access-keys')->with('message', 'Chiave di accesso inserita correttamente');
    }

    public function update(Request $request, AccessKey $key): Redirector|Application|RedirectResponse
    {
        $formFields = $request->validate([
            'key' => 'required'
        ]);
        $key->update($formFields);
        return redirect('/access-keys')->with('message', 'Chiave di accesso aggiornata con successo');
    }

    public function destroy(AccessKey $key): RedirectResponse
    {
        $key->delete();
        return back()->with('message', 'Chiave di accesso eliminata con successo');
    }


}
