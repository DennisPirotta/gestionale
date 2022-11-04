<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;

class EngagementController extends Controller
{
    public function index(){
        return view('engagement.index',[
            'holidays' => Holiday::with('user')->get(),
            'users' => User::with('hours')->get()
        ]);
    }
}
