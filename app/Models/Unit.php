<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    protected $fillable = [
        'name',
        'description',
        'address',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
