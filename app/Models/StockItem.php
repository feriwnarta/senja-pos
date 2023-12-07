<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class StockItem extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['items_id', 'minimum_stock', 'stock', 'init_avg_cost', 'init_last_cost', 'init_last_cost', 'created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 'USER NOT LOGIN';
            $model->created_by = $userId;
        });

        static::updating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 'USER NOT LOGIN';
            $model->updated_by = $userId;
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }


}
