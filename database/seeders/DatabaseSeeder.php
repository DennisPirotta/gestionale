<?php

namespace Database\Seeders;

use App\Models\AccessKey;
use App\Models\BusinessHour;
use App\Models\Customer;
use App\Models\Hour;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\TechnicalReport;
use App\Models\TechnicalReportDetails;
use App\Models\User;
use Doctrine\DBAL\Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(StaticSeeder::class);

        User::factory()->create([
            'level' => 2,
            'name' => 'Dennis',
            'surname' => 'Pirotta',
            'email' => 'dennispirotta@gmail.com',
            'password' => Hash::make('pellio2014'),
        ])->assignRole('boss');

        User::factory()->create([
            'name' => 'Prove',
            'surname' => 'Gestionale',
            'email' => 'provegestionale@test.com',
            'password' => Hash::make('pellio2014'),
        ])->assignRole('admin');


        AccessKey::factory()->create([
            'key' => Crypt::encryptString('3DAutomation'),
            'name' => 'default'
        ]);

        foreach (User::all() as $user) {
            BusinessHour::init($user);
        }




        Customer::factory(10)->create();
        User::factory(2)->create();
        Order::factory(50)->create();
        TechnicalReport::factory(200)->create();
        Hour::factory(300)->create();
        //Holiday::factory(30)->create();
        OrderDetails::factory(100)->create();
        TechnicalReportDetails::factory(100)->create();
        //AccessKey::factory(4)->create();

    }
}
