<?php

namespace App\CrossDocking\Src;

use App\Models\Catalog\Brand;
use App\Models\Catalog\Category;

class DockingData
{
    /**
     * Array of data content.
     *
     * @var array
     */
    protected $data;

    /**
     * Fill the data with a formated array.
     *
     * @param  array  $format
     * @param  array  $data
     *
     * @return self
     */
    public function fill(array $format, array $data)
    {
        $this->data = [];

        foreach ($data as $key => $value) {
            $this->data[isset($format[$key]) ? $format[$key] : $key] = $value;
        }

        return $this;
    }

    /**
     * Set a data key.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Get a data value by key.
     *
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    public function toArray()
    {
        $this->data['brand_id'] = Brand::saveIfNotExists($this->data['brand'])->id;
        $this->data['category_id'] = Category::saveIfNotExists($this->data['category']);

        return $this->data;
    }
}
