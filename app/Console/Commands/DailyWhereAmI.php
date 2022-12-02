<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DailyWhereAmI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whereami:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Whereami flag';

    public function handle(): int
    {
        foreach (User::all() as $user) {
            $user->update([
                'position' => false,
            ]);
        }

        return CommandAlias::SUCCESS;
    }
}
