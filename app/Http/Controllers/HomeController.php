<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        if (!auth()->user()->position){
            session()->put('whereami',false);
        }
        return view('home')->with('message', 'Login effettuato con successo');
    }
    public function store(): RedirectResponse
    {
        $user = auth()->user();
        if ($user !== null) {
            $user->first_login = false;
        }
        $user->save();
        return back();
    }
}
