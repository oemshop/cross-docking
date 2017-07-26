<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Execution extends Model
{
    public function feeds()
    {
        return $this->hasMany(ExecutionFeed::class);
    }
}
