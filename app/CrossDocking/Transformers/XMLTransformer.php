<?php

namespace App\CrossDocking\Transformers;

class XMLTransformer
{
    public function transformXml($filePath)
    {
        $data = [];
        $node = new \DOMDocument();
        $node->load($filePath);

        foreach ($node->getElementsByTagName("product") as $node) {
            $data[] = $this->showNode($node);
        }

        return $data;
    }

    protected function showNode($node)
    {
        $result = [];
        foreach ($node->childNodes as $child) {
            if ($this->hasChild($child)) {
                $result[$child->nodeName] = $this->showNode($child);
                continue;
            }

            $result[$child->nodeName] = $child->nodeValue ?: $child->textContent;
        }

        return $result;
    }

    protected function hasChild($node)
    {
        if (! $node->hasChildNodes()) {
            return false;
        }

        foreach ($node->childNodes as $child) {
            if ($child->nodeType != XML_ELEMENT_NODE) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function agroupData(array $data)
    {
        $result = [];

        foreach ($data as $d) {
            foreach ($d as $c) {
                $result[] = $c;
            }
        }

        return $result;
    }
}
