<?php

namespace Caldera\GeoBundle\Tests\DistanceCalculator;

use Caldera\GeoBasic\Coord\Coord;
use Caldera\GeoBundle\DistanceCalculator\DistanceCalculator;

class DistanceCalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testDistanceCalculator1()
    {
        $distanceCalculator = new DistanceCalculator();

        $hamburg = new Coord(53.550556, 9.993333);
        $berlin = new Coord(52.518611, 13.408333);

        $distance = $distanceCalculator
            ->addCoord($hamburg)
            ->addCoord($berlin)
            ->calculate()
        ;

        $this->assertEquals(269.83697059097, $distance);
    }
}