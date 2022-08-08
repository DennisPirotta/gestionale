<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Holiday;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class HolidayController extends Controller
{

    public function index()
    {

        $events = [];
        $users = User::select('id', 'name')->get();

        foreach (Holiday::all() as $holiday) {
            $editable = false;
            $user = $users->where('id', $holiday->user)->value('id');
            $title = $users->where('id', $holiday->user)->value('name');

            if ($holiday->user === auth()->user()->id) {
                $editable = true;
            }


            $color = 'rgba(215,239,79,0.84)'; // yellow ( default )
            $text = 'black'; // yellow ( default )
            $border = 'rgb(250,192,0)'; // yellow ( default )
            if ($holiday->approved) {
                $color = '#497e29'; // green
                $text = 'white';
                $border = 'rgb(32,70,15)';
            }

            $events[] = [
                'title' => $title,
                'start' => $holiday->start,
                'end' => $holiday->end,
                'id' => $holiday->id,
                'user' => $user,
                'editable' => $editable,
                'color' => $color,
                'textColor' => $text,
                'borderColor' => $border
            ];
        }

        return view('holidays.index', [
            'holidays' => $events
        ]);
    }

    // public function show(Holiday $holiday){}

    public function store(Request $request)
    {

        $formFields = $request->validate([
            'start' => 'required',
            'end' => 'required',
        ]);

        $formFields['user'] = auth()->user()->id;

        $dayCounter = auth()->user()->holidays;

        foreach (Holiday::where('user', auth()->user()->id)->get() as $day) {
            $dayCounter -= abs((strtotime($day->end) - strtotime($day->start)) / 86400);

        }

        $remaning = $dayCounter;

        $dayCounter -= abs((strtotime($formFields['end']) - strtotime($formFields['start'])) / 86400);

        if ($dayCounter >= 0) {
            Holiday::create($formFields);
        } else {
            return redirect("/ferie")->with('error', 'Disponibilità di ferie insufficente, disponi di <b>' . $remaning . '</b> giorni ');
        }

        return redirect('/ferie')->with('message', 'Ferie richiesta con successo');
    }

    public function create()
    {
        return view('holidays.create', [
            'holidays' => Holiday::all()
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer
        ]);
    }

    public function update(Request $request, Holiday $holiday)
    {

        $data = [];
        $used = 0;
        $userHolidays = auth()->user()->holidays;

        $request->start = DateTime::createFromFormat('Y-m-d', $request->start)->modify('+1 day')->format('Y-m-d');
        $request->end = DateTime::createFromFormat('Y-m-d', $request->end)->modify('+1 day')->format('Y-m-d');

        $data['start'] = $request->start;
        $data['end'] = $request->end;


        foreach (Holiday::where('user', auth()->user()->id)->get() as $holiday) {
            $used += Holiday::getWorkingDays($holiday->start, $holiday->end);

        }

        $used -= Holiday::getWorkingDays($request->old_start, $request->old_end);
        $used += Holiday::getWorkingDays($request->start, $request->end);

        if ($used <= $userHolidays) {
            $holiday->update($data);
        } else {
            return response('Disponibilità di ferie insufficente, disponi di <b>' . $remaning . '</b> giorni ', 500);

        }

        return response(
            json_encode([
                'message' => 'ferie aggiornate con successo, Inizio: <b>' . $request->start . '</b> Fine: <b>' . $request->end . '</b> Giorni utilizzati: <b>' . Holiday::getWorkingDays($request->start, $request->end) . '</b>',
                'perc' => ($userHolidays - $used) * 100 / $userHolidays,
                'left' => $userHolidays - $used
            ]),
            200
        );

    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('message', 'Cliente eliminato con successo');
    }

}
