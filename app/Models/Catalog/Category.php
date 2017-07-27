<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public static function saveIfNotExists(array $titles)
    {
        $parentId = null;

        foreach ($titles as $title) {
            $category = static::where('title', $title)
                ->where('parent_id', $parentId)
                ->first();

            if (count($category) == 0) {
                $category = new static;
                $category->parent_id = $parentId;
                $category->title = $title;
                $category->save();
            }

            $parentId = $category->id;
        }

        return $parentId;
    }
}
