<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMenu extends Model
{
    use HasFactory, HasUuids;

    protected $table = "stock_menus";
    protected $primaryKey = "id";
    protected $keyType = "string";

    protected $fillable = [''];
}
