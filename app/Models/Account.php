<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $fillable = [
        'name',
        'amount',
        'due_date',
        'status',
        'unit_id',
        'account_type_id',
        'payment_methods_id',
        'payment_date',
        'document_path',
        'interest_rate',
        'fine_amount',
        'amount_paid',
        'document_number',
        'description',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
