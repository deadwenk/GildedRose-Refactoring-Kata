<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GildedRose\GildedRose;

$recipies = [
    [
        'class' => 'BaseItem',
        'name' => '+5 Dexterity Vest',
        'sellIn' => 10,
        'quality' => 20,
    ],
    [
        'class' => 'Aged',
        'name' => 'Aged Brie',
        'sellIn' => 2,
        'quality' => 0,
    ],
    [
        'class' => 'BaseItem',
        'name' => 'Elixir of the Mongoose',
        'sellIn' => 5,
        'quality' => 7,
    ],
    [
        'class' => 'Sulfuras',
        'name' => 'Sulfuras, Hand of Ragnaros',
        'sellIn' => 0,
        'quality' => 80,
    ],
    [
        'class' => 'Sulfuras',
        'name' => 'Sulfuras, Hand of Ragnaros',
        'sellIn' => -1,
        'quality' => 80,
    ],
    [
        'class' => 'BackstagePasses',
        'name' => 'Backstage passes to a TAFKAL80ETC concert',
        'sellIn' => 15,
        'quality' => 20,
    ],
    [
        'class' => 'BackstagePasses',
        'name' => 'Backstage passes to a TAFKAL80ETC concert',
        'sellIn' => 10,
        'quality' => 49,
    ],
    [
        'class' => 'BackstagePasses',
        'name' => 'Backstage passes to a TAFKAL80ETC concert',
        'sellIn' => 5,
        'quality' => 49,
    ],
    [
        'class' => 'Conjured',
        'name' => 'Conjured Mana Cake',
        'sellIn' => 3,
        'quality' => 6,
    ],
];

$days = 2;
if (count($argv) > 1) {
    $days = (int) $argv[1];
}

$items = [];
foreach ($recipies as $recipie) {
    $class = '\\GildedRose\\Items\\' . $recipie['class'];
    $items[] = new $class($recipie['name'], $recipie['sellIn'], $recipie['quality']);
}

$app = new GildedRose($items);
$computed = [$app->getItemsAsArray()];

for ($day = 1; $day < $days; $day++) {
    for ($day = 1; $day < $days; $day++) {
        $app->updateQuality();
        $computed[] = $app->getItemsAsArray();
    }
}

$result = [
    'days' => $days,
    'items' => [],
];

foreach ($computed as $values) {
    foreach ($values as $item) {
        $id = $item['id'];
        if (! isset($result['items'][$id])) {
            $result['items'][$id] = [
                'name' => $item['name'],
                'class' => $item['class'],
                'sellIn' => [],
                'quality' => [],
            ];
        }
        $result['items'][$id]['sellIn'][] = $item['sellIn'];
        $result['items'][$id]['quality'][] = $item['quality'];
    }
}

file_put_contents(
    'tests/approvals/ApprovalTest.testTestFixture.approved.json',
    json_encode($result, JSON_PRETTY_PRINT)
);
