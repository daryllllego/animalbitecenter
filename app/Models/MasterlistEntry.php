<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterlistEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'time',
        'dose_received',
        'animal_status',
        'amount_paid',
        'payment_method',
        'remarks',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
