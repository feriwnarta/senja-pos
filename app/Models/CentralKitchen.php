<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class CentralKitchen extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'created_by',
        'updated_by',
    ];
    protected $keyType = 'string';

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

    public function warehouse(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_central_kitchens', 'central_kitchens_id', 'warehouses_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }

    public function item(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'items_central_kitchens', 'central_kitchens_id', 'items_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }


    public function centralProduction(): HasMany
    {
        return $this->hasMany(CentralProduction::class, 'central_kitchens_id');
    }
}
