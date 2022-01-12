<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Items\BaseItem;

final class GildedRose
{
    /**
     * @var BaseItem[]
     */
    private $items;

    /**
     * GildedRose contructor
     *
     * @param BaseItem[] $items the selling items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Get all computed items
     */
    public function getItemsAsArray(): array
    {
        return array_map(function (BaseItem $item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'class' => get_class($item),
                'sellIn' => $item->sell_in,
                'quality' => $item->quality,
            ];
        }, $this->items);
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $item->updateQuality();
        }
    }
}
