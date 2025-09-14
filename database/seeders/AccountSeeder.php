<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega as FKs existentes
        $units   = DB::table('units')->pluck('id', 'name');
        $types   = DB::table('account_types')->pluck('id', 'name');
        $methods = DB::table('payment_methods')->pluck('id', 'name');

        // Se algum estiver vazio, aborta
        if ($units->isEmpty() || $types->isEmpty() || $methods->isEmpty()) {
            $this->command->warn('⚠️ Popule units, account_types e payment_methods antes de rodar este seeder.');
            return;
        }

        $faker = \Faker\Factory::create('pt_BR');

        // Gera 24 meses (últimos 2 anos)
        $start = Carbon::now()->subMonths(23)->startOfMonth();

        $rows = [];
        $docNumber = 1000;

        for ($i = 0; $i < 24; $i++) {
            $mes = $start->copy()->addMonths($i);

            // cria de 2 a 3 contas por mês
            $qtd = rand(2, 3);

            for ($j = 0; $j < $qtd; $j++) {
                $status = $faker->randomElement(['paga', 'em aberto']);
                $amount = $faker->randomFloat(2, 100, 10000);

                $rows[] = [
                    'document_number'    => 'DEMO-' . str_pad($docNumber++, 5, '0', STR_PAD_LEFT),
                    'name'               => $faker->words(3, true),
                    'amount'             => $amount,
                    'due_date'           => $mes->copy()->addDays(rand(1, 25))->toDateString(),
                    'status'             => $status,
                    'unit_id'            => $units->random(),
                    'account_type_id'    => $types->random(),
                    'payment_methods_id' => $methods->random(),
                    'payment_date'       => $status === 'paga' ? $mes->copy()->addDays(rand(1, 28))->toDateString() : null,
                    'interest_rate'      => 0.00,
                    'fine_amount'        => 0.00,
                    'amount_paid'        => $status === 'paga' ? $amount : null,
                    'description'        => $faker->sentence(),
                    'document_path'      => null,
                    'updated_at'         => now(),
                    'created_at'         => now(),
                ];


            }
        }

        DB::table('accounts')->insert($rows);
    }
}
