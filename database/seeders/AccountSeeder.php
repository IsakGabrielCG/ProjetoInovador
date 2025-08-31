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
        // Map das FKs por nome (para facilitar os inserts)
        $units = DB::table('units')->pluck('id', 'name');                // ['Matriz' => 1, ...]
        $types = DB::table('account_types')->pluck('id', 'name');        // ['Despesa' => 1, 'Receita' => 2]
        $methods = DB::table('payment_methods')->pluck('id', 'name');    // ['PIX' => 1, 'Boleto' => 2, ...]

        $today = Carbon::today();

        // Use um document_number único e estável para evitar duplicata
        $rows = [
            [
                'document_number'   => 'DEMO-0001',
                'name'              => 'Conta de Luz',
                'amount'            => 350.75,
                'due_date'          => $today->copy()->addDays(10)->toDateString(),
                'status'            => 'em aberto',
                'unit'              => 'Matriz',
                'type'              => 'Despesa',
                'method'            => 'PIX',
                'payment_date'      => null,
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => null,
                'description'       => 'Energia elétrica mensal',
            ],
            [
                'document_number'   => 'DEMO-0002',
                'name'              => 'Conta de Água',
                'amount'            => 120.00,
                'due_date'          => $today->copy()->addDays(5)->toDateString(),
                'status'            => 'paga',
                'unit'              => 'Matriz',
                'type'              => 'Despesa',
                'method'            => 'Boleto',
                'payment_date'      => $today->copy()->addDays(3)->toDateString(),
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => 120.00,
                'description'       => 'Abastecimento de água',
            ],
            [
                'document_number'   => 'DEMO-0003',
                'name'              => 'Internet',
                'amount'            => 199.90,
                'due_date'          => $today->copy()->addDays(7)->toDateString(),
                'status'            => 'em aberto',
                'unit'              => 'Filial Sul',
                'type'              => 'Despesa',
                'method'            => 'Cartão de Crédito',
                'payment_date'      => null,
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => null,
                'description'       => 'Link dedicado',
            ],
            [
                'document_number'   => 'DEMO-0004',
                'name'              => 'Aluguel',
                'amount'            => 3500.00,
                'due_date'          => $today->copy()->addDays(1)->toDateString(),
                'status'            => 'em aberto',
                'unit'              => 'Filial Norte',
                'type'              => 'Despesa',
                'method'            => 'PIX',
                'payment_date'      => null,
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => null,
                'description'       => 'Imóvel comercial',
            ],
            [
                'document_number'   => 'DEMO-0005',
                'name'              => 'Salário',
                'amount'            => 10000.00,
                'due_date'          => $today->copy()->subDays(2)->toDateString(),
                'status'            => 'paga',
                'unit'              => 'Matriz',
                'type'              => 'Despesa', // (se usar “folha” como despesa)
                'method'            => 'Cartão de Débito',
                'payment_date'      => $today->copy()->subDay()->toDateString(),
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => 10000.00,
                'description'       => 'Folha de pagamento',
            ],
            [
                'document_number'   => 'DEMO-0006',
                'name'              => 'Venda Serviços',
                'amount'            => 8000.00,
                'due_date'          => $today->copy()->addDays(15)->toDateString(),
                'status'            => 'em aberto',
                'unit'              => 'Matriz',
                'type'              => 'Receita',
                'method'            => 'PIX',
                'payment_date'      => null,
                'interest_rate'     => 0.00,
                'fine_amount'       => 0.00,
                'amount_paid'       => null,
                'description'       => 'Contrato mensal de serviços',
            ],
        ];

        foreach ($rows as $r) {
            $unitId   = $units[$r['unit']] ?? null;
            $typeId   = $types[$r['type']] ?? null;
            $methodId = $methods[$r['method']] ?? null;

            // monta os dados para a tabela accounts
            $data = [
                'name'                 => $r['name'],
                'amount'               => $r['amount'],
                'due_date'             => $r['due_date'],
                'status'               => $r['status'],
                'unit_id'              => $unitId,
                'account_type_id'      => $typeId,
                'payment_methods_id'   => $methodId, // << coluna no plural conforme sua migration
                'payment_date'         => $r['payment_date'],
                'document_path'        => null,
                'interest_rate'        => $r['interest_rate'],
                'fine_amount'          => $r['fine_amount'],
                'amount_paid'          => $r['amount_paid'],
                'document_number'      => $r['document_number'],
                'description'          => $r['description'],
                'updated_at'           => now(),
                'created_at'           => now(),
            ];

            // upsert pela “chave” document_number para não duplicar em cada deploy
            DB::table('accounts')->updateOrInsert(
                ['document_number' => $r['document_number']],
                $data
            );
        }
    }
}
