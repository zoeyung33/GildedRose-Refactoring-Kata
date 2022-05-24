<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{

    private $_randNonNegativeSellInDate;
    private $_randNonNegativeQuality;

    private $_randNegativeSellInDate;
    private $_randNegativeQuality;

    private function setUpTestData($name) : array
    {
        $normalItem          = new Item($name, $this->_randNonNegativeSellInDate, $this->_randNonNegativeQuality);
        $expiredItem         = new Item($name, $this->_randNegativeSellInDate, $this->_randNonNegativeQuality);
        $expiredNegativeItem = new Item($name, $this->_randNegativeSellInDate, $this->_randNegativeQuality);
        return [$normalItem, $expiredItem, $expiredNegativeItem];
    }

    public function setUp() : void
    {
        $this->_randNonNegativeSellInDate = mt_rand(1, 50);
        $this->_randNonNegativeQuality    = mt_rand(1, 50);
        
        $this->_randNegativeSellInDate = mt_rand(-50, -1);
        $this->_randNegativeQuality    = mt_rand(-50, -1);
    }

    public function testFoo(): void
    {
        $itemName   = 'Foo';
        $items      = $this->setUpTestData($itemName);
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($itemName, $items[0]->name);
        $this->assertSame($this->_randNonNegativeSellInDate - 1, $items[0]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality - 1), $items[0]->quality);

        $this->assertSame($itemName, $items[1]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[1]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality - 2), $items[1]->quality);

        $this->assertSame($itemName, $items[2]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testBackstage(): void
    {
        $itemName   = 'Backstage passes to a TAFKAL80ETC concert';
        $items      = $this->setUpTestData($itemName);
        $gildedRose = new GildedRose($items);

        $testAnswerForQuality = GildedRose::calculateBackstageQuality($items[0]->sell_in, $this->_randNonNegativeQuality);
        $gildedRose->updateQuality();


        $this->assertSame($itemName, $items[0]->name);
        $this->assertSame($this->_randNonNegativeSellInDate - 1, $items[0]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($testAnswerForQuality), $items[0]->quality);

        $this->assertSame($itemName, $items[1]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[1]->sell_in);
        $this->assertSame(0, $items[1]->quality);

        $this->assertSame($itemName, $items[2]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testAgedBrie(): void
    {
        $itemName   = 'Aged Brie';
        $items      = $this->setUpTestData($itemName);
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($itemName, $items[0]->name);
        $this->assertSame($this->_randNonNegativeSellInDate - 1, $items[0]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality + 1), $items[0]->quality);

        $this->assertSame($itemName, $items[1]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[1]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality + 1), $items[1]->quality);

        $this->assertSame($itemName, $items[2]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testConjured(): void
    {
        $itemName   = 'Conjured';
        $items      = $this->setUpTestData($itemName);
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($itemName, $items[0]->name);
        $this->assertSame($this->_randNonNegativeSellInDate - 1, $items[0]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality - 2), $items[0]->quality);

        $this->assertSame($itemName, $items[1]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[1]->sell_in);
        $this->assertSame(GildedRose::qualityInGeneralRange($this->_randNonNegativeQuality - 4), $items[1]->quality);

        $this->assertSame($itemName, $items[2]->name);
        $this->assertSame($this->_randNegativeSellInDate - 1, $items[2]->sell_in);
        $this->assertSame(0, $items[2]->quality);
    }

    public function testSulfuras(): void
    {
        $itemName   = 'Sulfuras, Hand of Ragnaros';
        $items      = $this->setUpTestData($itemName);
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame($itemName, $items[0]->name);
        $this->assertSame($this->_randNonNegativeSellInDate, $items[0]->sell_in);
        $this->assertSame(80, $items[0]->quality);

        $this->assertSame($itemName, $items[1]->name);
        $this->assertSame($this->_randNegativeSellInDate, $items[1]->sell_in);
        $this->assertSame(80, $items[1]->quality);

        $this->assertSame($itemName, $items[2]->name);
        $this->assertSame($this->_randNegativeSellInDate, $items[2]->sell_in);
        $this->assertSame(80, $items[2]->quality);
    }
}
