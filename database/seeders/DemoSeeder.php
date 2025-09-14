<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call(AdminUserSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(AccountTypeSeeder::class);
        $this->call(UnitSeeder::class);

        DB::transaction(function () {
            $this->call([
                //AdminUserSeeder::class,
                //PaymentMethodSeeder::class,
                //AccountTypeSeeder::class,
                //UnitSeeder::class,
                AccountSeeder::class,
            ]);
        });
    }
}
