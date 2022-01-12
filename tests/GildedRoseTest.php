<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class GildedRoseTest extends TestCase
{
    private GildedRose $gildedRose;

    private array $approvalItems;

    protected function setUp(): void
    {
        $file = 'tests/approvals/ApprovalTest.testTestFixture.approved.json';
        $data = file_get_contents($file);

        if (! $data) {
            throw new FileException("Unable to read '${file}' file");
        }

        $data = json_decode($data, true);
        $this->approvalItems = $data;
    }

    /**
     * Compare item from GildedRose and test file
     */
    public function compareItem(array $a, array $b): void
    {
        $this->dump($b, 'expected');
        $this->dump($a, 'got     ');
        foreach ($a as $param => $value) {
            $this->assertNotContains($param, $b, "unknown param \"{$param}\"");
            $this->assertSame($b[$param], $value, "invalid param \"{$param}\" value: \"{$b[$param]}\" != \"{$value}\"");
        }
    }

    /**
     * Compare several items from GildedRose and test file
     */
    public function compareItems(array $a, array $b): void
    {
        foreach ($a as $itemA) {
            $contains = false;
            foreach ($b as $itemB) {
                if ($itemA['id'] === $itemB['id']) {
                    $contains = true;
                    break;
                }
            }
            if (! isset($itemB)) {
                throw new RuntimeException('Unable to find ' + $itemA['id'] + ' item id');
            }
            $this->assertTrue($contains, "\$a list item \"{$itemA['name']}\" does not contains in \$b list");
            $this->compareItem($itemA, $itemB);
        }
    }

    /**
     * Approval test
     */
    public function testApproval(): void
    {
        $initial = $this->sliceItemsDay($this->approvalItems['items']);
        $this->fill($initial);

        for ($day = 1; $day < $this->approvalItems['days']; $day++) {
            $this->gildedRose->updateQuality();
            $this->compareItems(
                $this->gildedRose->getItemsAsArray(),
                $this->sliceItemsDay($this->approvalItems['items'], $day)
            );
        }
    }

    /**
     * Fill GildedRose with initial data
     */
    private function fill(array $data): void
    {
        $items = [];
        foreach ($data as $blueprint) {
            $class = $blueprint['class'];
            $items[] = new $class($blueprint['name'], $blueprint['sellIn'], $blueprint['quality'], $blueprint['id']);
        }
        $this->gildedRose = new GildedRose($items);
    }

    /**
     * Getting items day slice
     */
    private function sliceItemsDay(array $items, int $day = 0, int $length = 1): array
    {
        $result = [];
        foreach ($items as $id => $item) {
            $sellIn = array_slice($item['sellIn'], $day, $length);
            $quality = array_slice($item['quality'], $day, $length);

            $result[] = [
                'id' => $id,
                'name' => $item['name'],
                'class' => $item['class'],
                'sellIn' => count($sellIn) === 1 ? $sellIn[0] : $sellIn,
                'quality' => count($quality) === 1 ? $quality[0] : $quality,
            ];
        }
        return $result;
    }

    /**
     * Print item state
     */
    private function dump(array $item, string $prefix = ''): void
    {
        echo $prefix . '  -> name: ' . $item['name'] . '; '
            . 'sellIn: ' . $item['sellIn'] . '; '
            . 'quality: ' . $item['quality'] . PHP_EOL;
    }
}
