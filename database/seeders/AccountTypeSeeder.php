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
        $items = [
            ['name' => 'Despesa', 'description' => 'Custos e contas a pagar'],
            ['name' => 'Receita', 'description' => 'Entradas de dinheiro'],
        ];

        foreach ($items as $i) {
            DB::table('account_types')->updateOrInsert(
                ['name' => $i['name']],
                ['name' => $i['name'], 'description' => $i['description'], 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }
}
