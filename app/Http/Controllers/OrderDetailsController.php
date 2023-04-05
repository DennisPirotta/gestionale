<?php

namespace App\Http\Controllers;

use App\Models\OrderDetails;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'signed' => ['required','boolean'],
            'order_id' => ['required','numeric'],
            'hour_id' => ['required'],
            'job_type_id' => ['required','numeric'],
        ]);
        OrderDetails::create($validated);
    }

    public function update(Request $request, OrderDetails $orderHour)
    {
        $request->validate([
            'job_type_id' => ['required','numeric']
        ]);
        $orderHour->update([
            'job_type_id' => $request->get('job_type_id')
        ]);
        return back();
    }
}
