<?php

namespace Caldera\GeoBundle\Test\DistanceCalculator;

use Caldera\GeoBasic\Coord\Coord;
use Caldera\GeoBundle\DistanceCalculator\SimpleDistanceCalculator;
use Caldera\GeoBundle\Tests\Mocks\SimpleMockedTrack;

class SimpleDistanceCalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testDistanceCalculator1()
    {
        $distanceCalculator = new SimpleDistanceCalculator();

        $hamburg = new Coord(53.550556, 9.993333);
        $berlin = new Coord(52.518611, 13.408333);

        $distance = $distanceCalculator
            ->addCoord($hamburg)
            ->addCoord($berlin)
            ->calculate()
        ;

        $this->assertEquals(269.83697059097, $distance);
    }

    public function testDistanceCalculator2()
    {
        $distanceCalculator = new SimpleDistanceCalculator();

        $track = new SimpleMockedTrack();

        $distance = $distanceCalculator
            ->addTrack($track)
            ->calculate();

        $this->assertEquals(1.8518590521829, $distance);
    }
}
