<?php

namespace App\Http\Controllers;

use App\Models\TechnicalReport;

class TechnicalReportDetailsController extends Controller
{
    public function show(TechnicalReport $technicalReport)
    {
        //return dd($technicalReport->technical_report_details);
    }
}
