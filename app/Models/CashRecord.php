<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'shift',
        'nurse_on_duty',
        'denom_1000',
        'denom_500',
        'denom_200',
        'denom_100',
        'denom_50',
        'denom_20',
        'denom_10',
        'denom_5',
        'denom_1',
        'remarks',
        'total_amount',
    ];
}
