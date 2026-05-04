<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Patient extends Model
{
    use HasFactory, HasBranch;

    protected $fillable = [
        'branch',
        'name',
        'age',
        'gender',
        'barangay',
        'city',
        'contact_number',
    ];
}
