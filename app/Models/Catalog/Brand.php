<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public static function saveIfNotExists($title)
    {
        $brand = static::where('title', $title)->first();

        if (count($brand) == 0) {
            $brand = new static;
            $brand->title = $title;
            $brand->save();
        }

        return $brand;
    }
}
