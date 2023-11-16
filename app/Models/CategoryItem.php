<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory, HasUuids;


    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['category_code', 'category_name'];
}
