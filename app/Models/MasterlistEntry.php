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
        'reference_number',
        'remarks',
        'nurse',
        'is_discounted',
        'discount_type',
        'discount_percentage',
        'original_amount',
        'is_split_payment',
        'cash_amount',
        'online_amount',
        'online_payment_method',
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
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
