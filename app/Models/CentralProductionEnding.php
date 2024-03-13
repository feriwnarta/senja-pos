<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentralProductionEnding extends Model
{
    use HasFactory, HasUuids;


    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

    public function targetItem(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'target_items_id');
    }
    
}
