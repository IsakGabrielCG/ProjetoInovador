<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'PIX',              'description' => 'Pagamento instantâneo'],
            ['name' => 'Boleto',           'description' => 'Boleto bancário'],
            ['name' => 'Cartão de Débito', 'description' => null],
            ['name' => 'Cartão de Crédito','description' => null],
            ['name' => 'Dinheiro',         'description' => null],
        ];

        foreach ($items as $i) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $i['name']],
                ['name' => $i['name'], 'description' => $i['description'], 'updated_at' => now(), 'created_at' => now()],
            );
        }

    }
}
