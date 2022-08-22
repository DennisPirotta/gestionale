<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

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
    protected $description = 'Command description';


    public function handle(): void
    {
        $users = User::where('position',true)->get();
        foreach ($users as $user){
            $user->update([
                'position' => false,
            ]);
        }
    }
}
