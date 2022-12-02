<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;

class BusinessHourController extends Controller
{
    public function index()
    {
        return view('users.business-hours.index', [
            'hours' => BusinessHour::where('user_id', auth()->id())->get(),
        ]);
    }
}
