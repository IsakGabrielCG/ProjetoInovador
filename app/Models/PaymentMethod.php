<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $fillable = [
        'name',
        'description',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
