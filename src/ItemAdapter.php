<?php

declare(strict_types=1);

namespace GildedRose;

use RuntimeException;

class ItemAdapter
{
    public int $id;

    /**
     * @var Item
     */
    private $item;

    /**
     * Item constructor
     *
     * @param string $name Item name
     * @param int $sell_in Item sell in
     * @param int $quality Item quality
     * @param int $id Item id
     */
    public function __construct(string $name, int $sell_in, int $quality, $id = null)
    {
        if ($quality < 0) {
            throw new RuntimeException('quality must be positive value');
        }

        $this->item = new Item($name, $sell_in, $quality);

        if ($id === null) {
            $this->generateId();
        } else {
            $this->id = $id;
        }
    }

    /**
     * Magic method __get
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->item->{$name};
    }

    /**
     * Magic method __set
     *
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->item->{$name} = $value;
    }

    /**
     * Generate item id
     */
    private function generateId(): void
    {
        $time = microtime(true);
        $seconds = (int) round($time);
        $micro = $time - $seconds;
        $this->id = $seconds + (int) round($micro * 1e6);
    }
}
