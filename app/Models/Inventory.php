<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'shift'];

    public function entries()
    {
        return $this->hasMany(InventoryEntry::class);
    }
}
