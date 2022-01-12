<?php

declare(strict_types=1);

namespace GildedRose;

interface ItemInterface
{
    /**
     * Updates item quality
     */
    public function updateQuality(): void;

    /**
     * Updates sell in
     */
    public function updateSellIn(): void;
}
