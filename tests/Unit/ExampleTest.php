<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testBasicTest()
    {
        $hayamax = new \App\CrossDocking\Providers\Hayamax();
        $hayamax->download();

        die('oi');

        dd(\App\Models\Core\Provider::all());
        dd(\App\Models\Core\Execution::with(['feeds', 'feeds.items'])->get()->toArray());
        dd(\App\Models\Catalog\Product::all());
    }
}
