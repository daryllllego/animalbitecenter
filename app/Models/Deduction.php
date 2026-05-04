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
    ];
}
