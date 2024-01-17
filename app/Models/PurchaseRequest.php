<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends Model
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


    public function reference(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequestRef::class, 'purchase_request_refs_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(PurchaseRequestHistory::class, 'purchase_requests_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'purchase_requests_id');
    }
}
