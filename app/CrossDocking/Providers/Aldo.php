<?php

namespace App\CrossDocking\Providers;

use App\CrossDocking\Src\DockingProvider;

class Aldo extends DockingProvider
{
    // @todo all

    protected $parameters = ['user', 'password'];

    protected $fields = [
        'sku' => 'code',
        'titulo' => 'title',
    ];

    public function download()
    {
        echo 'maoe';
    }

    protected function read()
    {
        return [
            [
                'sku' => 1,
                'titulo' => 'oi',
                'price' => 1,
                'quantity' => 1,
            ],
            [
                'sku' => 2,
                'title' => 'ola',
                'price' => 2,
                'quantity' => 2,
            ],
            [
                'sku' => 1,
                'title' => 'oi2',
                'price' => 3,
                'quantity' => 0,
            ]
        ];
    }

    protected function format(array $data)
    {
        return $data;
    }
}
