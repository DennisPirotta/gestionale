<?php

namespace App\Console\Commands;

use App\Mail\HolidayNotifyMail;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Command\Command as CommandAlias;

class NotifyHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $holidays = Holiday::with('user')->where('start', Carbon::tomorrow())->get();
        if ($holidays->count() > 0) {
            $grouped = $holidays->groupBy('user.company_id');
            if ($grouped->get(1,collect([]))->count() > 0) {
                Mail::to('amministrazione@3dautomation.it')
                    ->cc([
                        'administration@sphtechnology.ch',
                        'angelo.dariol@sphtechnology.ch',
                        'andrea.dariol@sphtechnology.ch'
                    ])
                    ->send(new HolidayNotifyMail($grouped->get(1)));
            }
            if ($grouped->get(2,collect([]))->count() > 0) {
                Mail::to('administration@sphtechnology.ch')
                    ->cc([
                        'amministrazione@3dautomation.it',
                        'angelo.dariol@sphtechnology.ch',
                        'andrea.dariol@sphtechnology.ch'
                    ])->send(new HolidayNotifyMail($grouped->get(2)));
            }
        }
        return CommandAlias::SUCCESS;
    }
}
