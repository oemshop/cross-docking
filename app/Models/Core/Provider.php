<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public static function associate($title)
    {
        $provider = static::where('title', $title);

        if ($provider->count() > 0) {
            return $provider->first();
        }

        $provider = new static;
        $provider->title = $title;
        $provider->save();

        return $provider;
    }

    public function parameters()
    {
        return $this->hasMany(ProviderParameter::class);
    }

    public function executions()
    {
        return $this->hasMany(Execution::class);
    }

    public function products()
    {
        return $this->hasMany(\App\Models\Catalog\Product::class);
    }
}
