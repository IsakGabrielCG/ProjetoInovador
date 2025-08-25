<?php

namespace App\Helpers;

use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Carbon;

class Financeiro
{

     public static function calcularValorPago(Get $get): ?string
    {
        $amountRaw   = $get('amount');          // valor base
        $multaRaw    = $get('fine_amount');     // multa %
        $jurosRaw    = $get('interest_rate');   // juros % ao dia
        $due         = $get('due_date');        // vencimento
        $paid        = $get('payment_date');    // pagamento
        $status      = $get('status');

        $toFloat = fn($v) => ($v === null || $v === '') ? 0.0 : (float) str_replace(['.', ','], ['', '.'], $v);

        $amount   = $toFloat($amountRaw);
        $multaPct = $toFloat($multaRaw);
        $jurosDia = $toFloat($jurosRaw);

        if ($amount <= 0) {
            return null;
        }

        // se não está pago → retorna só valor base
        if ($status !== 'paga' || !$paid) {
            return number_format($amount, 2, ',', '.');
        }

        // dias de atraso
        $diasAtraso = 0;
        if ($due) {
            try {
                $p = Carbon::parse($paid);
                $d = Carbon::parse($due);
                if ($p->gt($d)) {
                    $diasAtraso = $d->diffInDays($p);
                }
            } catch (\Throwable $e) {
                $diasAtraso = 0;
            }
        }

        $multa = ($diasAtraso > 0 && $multaPct > 0) ? $amount * ($multaPct / 100) : 0.0;
        $juros = ($diasAtraso > 0 && $jurosDia > 0) ? $amount * ($jurosDia / 100) * $diasAtraso : 0.0;

        $total = $amount + $multa + $juros;

        return number_format($total, 2, ',', '.'); // formato pt-BR
    }

}
