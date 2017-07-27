<?php

namespace App\CrossDocking\Transformers;

trait HayamaxTransformer
{
    public function transformXml($filePath)
    {
        $doc = new \DOMDocument();
        $doc->load($filePath);
        $products = $doc->getElementsByTagName("product");
        $data = [];
        foreach ($products as $key => $product) {
            $data[$key] = [];
            foreach($product->childNodes as $child) {
                $data[$key][$child->nodeName] = $child->textContent;
            }
        }
        $data = json_decode(json_encode($data));
        $data->images = array_map(function ($image) {
            return 'https://'.$data->images;
        }, explode('https://', $data->images));

        return $data;
    }
}
