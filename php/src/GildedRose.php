<?php

declare(strict_types=1);

namespace GildedRose;

/**
 * GildedRose-Refactoring-Kata - Kaboodle Technical Test
 *
 * @author   Zoe Yung <yungchingzoe@gmail.com>
 */
final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Restrict quality in general range
     * 
     * @param  $quality
     * @return int
     */
    public static function qualityInGeneralRange($quality) : int
    {
        $max = 50;
        $min = 0;
        return $quality < $max ? ($quality > $min ? $quality : $min) : $max ;
    }

    /**
     * Special Calculation the quality for Backstage items
     * 
     * @param  $sell_in, $original_quality
     * @return int
     */
    public static function calculateBackstageQuality($sell_in, $original_quality): int
    {
        switch (true) {
            case (10 < $sell_in):
                $quality = $original_quality - 1;
                break;
            case (5 < $sell_in) && ($sell_in <= 10):
                $quality = $original_quality + 2;
                break;
            case (0 < $sell_in) && ($sell_in <= 5):
                $quality = $original_quality + 3;
                break;
            case (0 > $sell_in):
                $quality = 0;
                break;
        }

        return $quality;
    }

    /**
     * Special Formula the quality for Aged Brie item
     * 
     * @param  Item $item
     * @return int
     */
    private function AgedBrieFormula(Item $item) : int
    {
        $quality = ($item->quality < 50) ? $item->quality + 1 : 50;
        return $this->qualityInGeneralRange($quality);
    }

    /**
     * Special Formula the quality for Backstage item
     * 
     * @param  Item $item
     * @return int
     */
    public function BackstageFormula(Item $item) : int
    {
        $quality = $this->calculateBackstageQuality($item->sell_in, $item->quality);
        return $this->qualityInGeneralRange($quality);
    }

    /**
     * Special Formula the quality for Conjured item
     * 
     * @param  Item $item
     * @return int
     */
    private function ConjuredFormula(Item $item) : int
    {
        $quality = ($item->sell_in > 0) ? $item->quality - 2 : $item->quality - 4;
        return $this->qualityInGeneralRange($quality);
    }

    /**
     * Special Formula the quality for Normal item
     * 
     * @param  Item $item
     * @return int
     */
    private function NormalItemFormula(Item $item) : int
    {
        $quality = ($item->sell_in > 0) ? $item->quality - 1 : $item->quality - 2;
        return $this->qualityInGeneralRange($quality);
    }

    /**
     * Updating the Quality by the type of items
     * 
     * @return void
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            switch ($item->name) {
                case 'Sulfuras, Hand of Ragnaros':
                    $calculatedResult = 80;
                    break;
                case 'Aged Brie':
                    $calculatedResult = $this->AgedBrieFormula($item);
                    break;
                case 'Backstage passes to a TAFKAL80ETC concert':
                    $calculatedResult = $this->BackstageFormula($item);
                    break;
                case 'Conjured':
                    $calculatedResult = $this->ConjuredFormula($item);
                    break;
                default:
                    $calculatedResult = $this->NormalItemFormula($item);
                    break;
            }

            $item->sell_in = ($item->name == 'Sulfuras, Hand of Ragnaros') ? $item->sell_in : $item->sell_in - 1;
            $item->quality = $calculatedResult;
        }
    }
}
