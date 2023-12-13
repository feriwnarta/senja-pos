<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rack extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];


    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function itemPlacement(): HasMany
    {
        return $this->hasMany(ItemPlacement::class, 'racks_id');
    }
}
