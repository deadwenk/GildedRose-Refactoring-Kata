<?php

declare(strict_types=1);

namespace GildedRose\Items;

class BackstagePasses extends BaseItem
{
    protected int $qualityStepSign = self::POSITIVE_QUALITY_SIGN;

    public function updateSellIn(): void
    {
        parent::updateSellIn();

        if ($this->sell_in >= 5 && $this->sell_in < 10) {
            $this->qualityStep = 2;
        }

        if ($this->sell_in > 0 && $this->sell_in < 5) {
            $this->qualityStep = 3;
        }
    }

    public function step(): void
    {
        parent::step();

        if ($this->sell_in < 0) {
            $this->quality = 0;
        }
    }
}
