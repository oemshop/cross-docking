<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Catalog\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DockingFeedTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @dataProvider feedOperationsProvider
     */
    public function testFeeds(array $oldData, Product $product, $type, $title)
    {
        $execution = factory(\App\Models\Core\Execution::class)->create();
        $feed = new \App\CrossDocking\Src\DockingFeed($execution);

        $execFeeds = $feed->sync($oldData, $product);
        $prodFeed = $execFeeds->first()->items()->where('product_id', $product->id);

        $this->assertEquals(true, $prodFeed->count() > 0);
        $this->assertEquals($type, $execFeeds->first()->type);
        $this->assertEquals($title, $execFeeds->first()->title);
    }

    public function feedOperationsProvider()
    {
        $this->refreshApplication();
        $this->runDatabaseMigrations();
        $this->beginDatabaseTransaction();

        $productData = factory(Product::class)->create(['quantity' => 2, 'price' => 2]);

        return [
            [ [], $productData, 'S', 'Inserido' ],
            [ $productData->toArray(), $productData, 'N', 'Nenhuma alteração' ],
            [ $productData->toArray(), factory(Product::class)->create(['quantity' => 2, 'price' => 3]), 'W', 'Aumento de custo' ],
            [ $productData->toArray(), factory(Product::class)->create(['quantity' => 2, 'price' => 1]), 'W', 'Redução de custo' ],
        ];
    }
}
