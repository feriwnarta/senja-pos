<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class CentralProductionFinish extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

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

    public function request(): BelongsTo {
        return  $this->belongsTo(CentralProductionRequestMaterial::class, 'central_productions_id');
    }

    public function production(): BelongsTo {
        return  $this->belongsTo(CentralProduction::class, 'central_productions_id');
    }

    public function details() : HasMany {
        return $this->hasMany(CentralProductionFinishDetail::class,'central_production_finishes_id');
    }

    public function items(): BelongsTo {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
