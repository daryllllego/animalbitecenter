<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'vaccine_name',
        'quantity',
        'received',
        'transferred',
        'used',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
