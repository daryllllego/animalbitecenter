<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class DailyRecord extends Model
{
    use HasFactory, HasBranch;

    protected $fillable = [
        'branch',
        'date',
        'opening_cash',
        'online_sales',
    ];
}
