<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public function parameters()
    {
        return $this->hasMany(ProviderParameter::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Catalog\Product::class);
    }
}
