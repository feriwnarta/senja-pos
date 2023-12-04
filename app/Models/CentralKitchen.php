<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $userId = Auth::check() ? Auth::id() : 'USER NOT LOGIN';
            $model->created_by = $userId;
        });
    }
}
