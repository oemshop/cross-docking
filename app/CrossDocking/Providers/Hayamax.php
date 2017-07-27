<?php

namespace App\CrossDocking\Providers;

use App\CrossDocking\Src\DockingProvider;
use App\CrossDocking\Transformers\Charcase;
use App\CrossDocking\Transformers\XMLTransformer;

class Hayamax extends DockingProvider
{

    protected $parameters = ['user', 'password'];

    protected $fields = [
        'prod_id' => 'code',
        'prod_name' => 'title',
        'stock' => 'quantity',
        'seg_name' => 'category',
    ];

    public function download()
    {
        $path = storage_path('crossdocking/'.$this->provider->id);

        if (! file_exists($path)) {
            \File::makeDirectory($path, 0777, true, true);
        }

        $file = file_get_contents('http://webmax.hayamax.com.br/crossdock/servlet/CrossDockingServlet.class.php?action=crossDockingPrice&customerId=334993&compress=0&canal=CD');
        file_put_contents($path.'/'.$this->provider->id.'__'.date('Y-m-d-H-i-s').'.xml', $file);
    }

    protected function read()
    {
        $transformer = new XMLTransformer();
        $files = \File::allFiles(storage_path('crossdocking/'.$this->provider->id));

        return $transformer->agroupData(array_map(function ($file) use ($transformer) {
            return $transformer->transformXml($file->getPathname());
        }, $files));
    }

    protected function format(array $data)
    {
        $data['prod_name'] = Charcase::regularize($data['prod_name']);
        $data['seg_name'] = explode('##', $data['seg_name']);

        return $data;
    }
}
