<?php

namespace App\CrossDocking\Src;

use App\Models\Core\Provider;
use App\Models\Core\Execution;
use App\Models\Catalog\Product;
use App\Models\Core\ProviderParameter;

abstract class DockingProvider
{
    protected $title;
    protected $provider;
    protected $parameters;
    protected $fields;
    private $data;

    public function __construct()
    {
        $this->data = new DockingData();
        $this->provider = Provider::associate($this->title ?? class_basename($this));
        ProviderParameter::associate($this->provider, $this->parameters);
    }

    public function process()
    {
        $dockingData = $this->read();
        $execution = (new Execution())->start($this->provider->id, count($dockingData));
        $dockingFeed = new DockingFeed($execution);

        foreach ($dockingData as $key => $data) {
            $this->data->fill($this->fields, $this->format($data));
            $product = $this->provider->products()->where('code', $this->data->code)->first();

            if (count($product) == 0) {
                $product = new Product;
            }

            $oldData = $product->toArray();
            $newData = $product->fill($this->data->toArray());

            if ($oldData != $newData->toArray()) {
                $product = $this->provider->products()->save($newData);
            }

            $dockingFeed->sync($oldData, $product);

            dd(\App\Models\Catalog\Category::all());
        }

        $execution->finish();
    }

    abstract public function download();

    abstract protected function read();

    abstract protected function format(array $data);
}
