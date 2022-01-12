<?php

declare(strict_types=1);

namespace GildedRose\Items;

use GildedRose\ItemAdapter;
use GildedRose\ItemInterface;

/**
 * GildedRose base item class
 *
 * @property int $id
 * @property string $name
 * @property int $sell_in
 * @property int $quality
 */
class BaseItem extends ItemAdapter implements ItemInterface
{
    /**
     * Positive quality step sign
     */
    public const POSITIVE_QUALITY_SIGN = 1;

    /**
     * Negative quality step sign
     */
    public const NEGATIVE_QUALITY_SIGN = -1;

    protected int $qualityStepSign = self::NEGATIVE_QUALITY_SIGN;

    protected int $qualityStep = 1;

    public function updateSellIn(): void
    {
        $this->sell_in--;
    }

    public function updateQuality(): void
    {
        $this->updateSellIn();

        if ($this->sell_in < 0) {
            $this->qualityStep = 2;
        }

        $this->step();
    }

    /**
     * Step function
     */
    protected function step(): void
    {
        $this->quality += $this->qualityStepSign * $this->qualityStep;

        if ($this->quality > 50) {
            $this->quality = 50;
        }

        if ($this->quality < 0) {
            $this->quality = 0;
        }
    }
}
