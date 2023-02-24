<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
    public function handle()
    {
        $holidays = Holiday::whereDate('start', Carbon::now())
                            ->orWhereDate('start', Carbon::tomorrow());
        $holidays->each(fn (Holiday $holiday) => $holiday->notifyHoliday());
        return CommandAlias::SUCCESS;
    }
}
