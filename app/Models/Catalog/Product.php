<?php

namespace App\Models\Catalog;

use App\CrossDocking\Src\DockingData;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'brand_id',
        'category_id',
        'code',
        'title',
        'price',
        'quantity',
    ];

    public function feedItems()
    {
        return $this->hasMany(\App\Models\Core\ExecutionFeedItem::class);
    }
}
