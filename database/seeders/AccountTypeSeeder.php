<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            ['name' => 'Energia Elétrica', 'description' => 'Contas de luz'],
            ['name' => 'Água',             'description' => 'Abastecimento'],
            ['name' => 'Internet',         'description' => 'Conectividade'],
            ['name' => 'Aluguel',          'description' => 'Locação de imóveis'],
            ['name' => 'Salários',         'description' => 'Folha de pagamento'],
            ['name' => 'Vendas de Serviços','description' => 'Receitas de serviços'],
            ['name' => 'Manutenção',       'description' => null],
            ['name' => 'Impostos',         'description' => null],
            ['name' => 'Outros',           'description' => null],
        ];

        foreach ($categorias as $c) {
            DB::table('account_types')->updateOrInsert(
                ['name' => $c['name']],
                [
                    'name'        => $c['name'],
                    'description' => $c['description'],
                    'updated_at'  => now(),
                    'created_at'  => now(),
                ],
            );
        }
    }
}
