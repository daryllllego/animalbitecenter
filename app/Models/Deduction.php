<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Deduction extends Model
{
    use HasFactory, HasBranch;

    protected $fillable = [
        'branch',
        'amount',
        'description',
        'date',
        'released_by',
        'released_to',
        'denom_1000',
        'denom_500',
        'denom_200',
        'denom_100',
        'denom_50',
        'denom_20',
        'coin_20',
        'coin_10',
        'coin_5',
        'coin_1',
        'status',
    ];
}
