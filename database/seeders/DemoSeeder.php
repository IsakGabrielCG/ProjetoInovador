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


        DB::transaction(function () {
            $this->call([
                AdminUserSeeder::class,
                PaymentMethodSeeder::class,
                AccountTypeSeeder::class,
                UnitSeeder::class,
                AccountSeeder::class,
            ]);
        });
    }
}
