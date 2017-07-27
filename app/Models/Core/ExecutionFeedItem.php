<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ExecutionFeedItem extends Model
{
    protected $fillable = ['product_id'];

    public function feed()
    {
        return $this->belongsTo(ExecutionFeed::class, 'execution_feed_id', 'id');
    }
}
