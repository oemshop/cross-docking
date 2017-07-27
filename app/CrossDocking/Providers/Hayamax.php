<?php

namespace App\CrossDocking\Providers;

use App\CrossDocking\Src\DockingProvider;
use App\CrossDocking\Transformers\HayamaxTransformer;

class Hayamax extends DockingProvider
{
    use HayamaxTransformer;

    protected $parameters = ['user', 'password'];

    protected $fields = [
        'sku' => 'code',
        'titulo' => 'title',
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
        $files = \File::allFiles(storage_path('crossdocking/'.$this->provider->id));
        $data = array_walk($files, function ($file) {
            $data = $this->transformXml($file->getPathname());
            dd($data);
        });

        foreach ($files as $file) {

            foreach ($doc->load($file->getPathname)->getElementsByTagName("product") as $key => $destination) {
              $data[$key] = [];
              foreach($destination->childNodes as $child) {
                  $data[$key][$child->nodeName] = $child->textContent;
                  if ($child->nodeType == XML_CDATA_SECTION_NODE) {
                      echo $child->textContent . "asd<br/>";
                  }
              }
            }

            $formatedData = array_merge(isset($formatedData) ? $formatedData : [], json_decode(json_encode($xml->getContent()))->product);
        }

        foreach ($formatedData as $data) {
            dd($data->brand);
        }
    }

    protected function format(array $data)
    {
        return $data;
    }
}
