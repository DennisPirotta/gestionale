<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{

    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
    * @return View
    */
    public function view(): View
    {
        return view('components.invoices-table', [
            'invoices' => $this->users
        ]);
    }
}
