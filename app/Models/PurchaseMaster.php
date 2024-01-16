<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseMaster extends Model
{
    use HasFactory;

    public function reference(): BelongsTo
    {
        return $this->belongsTo(PurchaseRef::class, 'purchase_refs_id');
    }
}
