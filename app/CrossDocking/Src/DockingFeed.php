<?php

namespace App\CrossDocking\Src;

use App\Models\Catalog\Product;
use App\Models\Core\Execution;
use App\Models\Core\ExecutionFeed;
use App\Models\Core\ExecutionFeedItem;

class DockingFeed
{
    protected $execution;

    public function __construct(Execution $execution)
    {
        $this->execution = $execution;
    }

    public function sync(array $oldData, Product $product)
    {
        if (count($oldData) == 0) {
            return $this->makeFeed('S', 'Inserido', 'Novos produtos cadastrados', $product->id);
        }

        if ($oldData['price'] < $product->price) {
            $this->makeFeed('W', 'Aumento de custo', 'Produtos estão mais caros', $product->id);
        }

        if ($oldData['price'] > $product->price) {
            $this->makeFeed('W', 'Redução de custo', 'Produtos estão mais baratos', $product->id);
        }

        if ($oldData['quantity'] > 0 && $product->quantity == 0) {
            $this->makeFeed('W', 'Estoque esgotado', 'Produtos sem estoque', $product->id);
        }

        if ($oldData['quantity'] == 0 && $product->quantity > 0) {
            $this->makeFeed('W', 'Estoque renovado', 'Produtos novamente com estoque', $product->id);
        }
    }

    protected function makeFeed($type, $title, $message, $productId = null)
    {
        $feed = $this->execution->feeds()
            ->where('type', $type)
            ->where('title', $title)
            ->where('message', $message)
            ->first();

        if (count($feed) == 0) {
            $feed = $this->execution->feeds()->save(new ExecutionFeed([
                'type' => $type,
                'title' => $title,
                'message' => $message,
            ]));
        }

        if ($productId > 0 && $feed->items()->where('product_id', $productId)->count() == 0) {
            $feed->items()->save(new ExecutionFeedItem(['product_id' => $productId]));
        }
    }
}
