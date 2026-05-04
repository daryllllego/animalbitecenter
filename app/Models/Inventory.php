<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasBranch;

class Inventory extends Model
{
    use HasFactory, HasBranch;

    protected $fillable = ['branch', 'date', 'shift'];

    public function entries()
    {
        return $this->hasMany(InventoryEntry::class);
    }
}
