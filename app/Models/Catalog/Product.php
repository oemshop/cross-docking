<?php

namespace App\Models\Catalog;

use App\CrossDocking\Src\DockingData;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'title',
        'price',
        'quantity'
    ];
}
