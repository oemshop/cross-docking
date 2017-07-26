<?php

namespace App\Models\Core;

use App\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;

class ExecutionFeed extends Model
{
    protected $fillable = ['type', 'title', 'message'];

    public function items()
    {
        return $this->hasMany(ExecutionFeedItem::class);
    }
}
