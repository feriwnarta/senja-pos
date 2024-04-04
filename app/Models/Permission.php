<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';
    protected $fillable = ['name', 'permission_categories_id', 'guard_name'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PermissionCategory::class, 'permission_categories_id');
    }
}
