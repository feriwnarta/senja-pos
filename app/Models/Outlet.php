<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Outlet extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'central_kitchens_id',
        'code',
        'name',
        'address',
        'phone',
        'email',
        'created_by',
        'updated_by',
    ];

    public function warehouse(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_outlets', 'outlets_id', 'warehouses_id')->using(new class extends Pivot {
            use  HasUuids;
        })->withTimestamps();
    }


}
