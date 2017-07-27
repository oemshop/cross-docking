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
            $this->makeFeed('INSERT', 'Inserido', 'Novos produtos cadastrados', $product->id);

            return $this->execution->feeds()->get();
        }

        if ($oldData['price'] < $product->price) {
            $this->makeFeed('COST_UP', 'Aumento de custo', 'Produtos estão mais caros', $product->id);
        }

        if ($oldData['price'] > $product->price) {
            $this->makeFeed('COST_DOWN', 'Redução de custo', 'Produtos estão mais baratos', $product->id);
        }

        if ($oldData['quantity'] > 0 && $product->quantity == 0) {
            $this->makeFeed('OUT_OF_STOCK', 'Estoque esgotado', 'Produtos sem estoque', $product->id);
        }

        if ($oldData['quantity'] == 0 && $product->quantity > 0) {
            $this->makeFeed('IN_STOCK', 'Estoque renovado', 'Produtos novamente com estoque', $product->id);
        }

        if ($oldData == $product->toArray()) {
            $this->makeFeed('NONE', 'Nenhuma alteração', 'Produtos sem nenhuma alteração', $product->id);
        }

        return $this->execution->feeds()->get();
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
