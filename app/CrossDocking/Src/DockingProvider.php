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
    private $output;

    public function __construct()
    {
        $this->data = new DockingData();
        $this->title = $this->title ?? class_basename($this);
        $this->provider = Provider::where('title', $this->title)->first();

        if (count($this->provider) == 0) {
            $this->provider = new Provider();
            $this->provider->title = $this->title;
            $this->provider->save();
        }

        if (is_array($this->parameters) && count($this->parameters) > 0) {
            foreach ($this->parameters as $param) {
                if ($this->provider->parameters()->where('key', $param)->count() > 0) {
                    continue;
                }

                $this->provider->parameters()->save(new ProviderParameter(['param' => $param]));
            }
        }
    }

    public function process()
    {
        $dockingData = $this->read();
        $execution = new Execution();
        $execution->provider_id = $this->provider->id;
        $execution->data_rows = count($dockingData);
        $execution->started_at = date('Y-m-d H:i:s');
        $execution->save();
        $dockingFeed = new DockingFeed($execution);
        $this->print('Reading data from '.$execution->data_rows.' rows');

        foreach ($dockingData as $key => $data) {
            $this->print('-- '.$key.'/'.$execution->data_rows);
            $this->data->fill($this->fields, $this->format($data));
            $product = $this->provider->products()->where('code', $this->data->code)->first();

            if (count($product) == 0) {
                $product = new Product;
            }

            $oldData = $product->toArray();
            $product = $this->provider->products()->save($product->fill($this->data->toArray()));

            $dockingFeed->sync($oldData, $product);
        }

        $this->print('Done!');
        $execution->finished = true;
        $execution->finished_at = date('Y-m-d H:i:s');
        $execution->save();
    }

    public function setOutput(\Closure $output)
    {
        $this->output = $output;

        return $this;
    }

    protected function print($message)
    {
        if ($this->output != null) {
            $this->output($message);
        }

        return $this;
    }

    abstract public function download();

    abstract protected function read();

    abstract protected function format(array $data);
}
