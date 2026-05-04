<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class MasterlistEntry extends Model
{
    use HasFactory, HasBranch;

    protected $fillable = [
        'branch',
        'patient_id',
        'time',
        'dose_received',
        'animal_status',
        'amount_paid',
        'payment_method',
        'remarks',
        'nurse',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
