<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BusinessHour;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Cmixin\BusinessTime;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $hours = BusinessHour::where('user_id',$user->id)->get();
        $data = [];
        foreach ($hours as $hour){
            $data[$hour->week_day] = [
                Carbon::parse($hour->morning_start)->format('H:i') . "-" . Carbon::parse($hour->morning_end)->format('H:i') ,
                Carbon::parse($hour->afternoon_start)->format('H:i') . "-" . Carbon::parse($hour->afternoon_end)->format('H:i')
            ];
        }
        $data['saturday'] = [];
        $data['sunday'] = [];
        Carbon::setOpeningHours($data);
    }
}
