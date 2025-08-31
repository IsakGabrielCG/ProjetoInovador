<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Matriz',     'cnpj' => '12345678000190', 'address' => 'Av. Central, 1000'],
            ['name' => 'Filial Sul', 'cnpj' => '11222333000155', 'address' => 'Rua das Flores, 200'],
            ['name' => 'Filial Norte','cnpj' => '99888777000111', 'address' => 'Av. Brasil, 300'],
        ];

        foreach ($items as $i) {
            DB::table('units')->updateOrInsert(
                ['cnpj' => $i['cnpj']], // usa CNPJ como “chave natural”
                ['name' => $i['name'], 'cnpj' => $i['cnpj'], 'address' => $i['address'], 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }
}
