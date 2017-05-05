<?php

namespace Caldera\GeoBundle\Test\DistanceCalculator;

use Caldera\GeoBasic\Coord\Coord;
use Caldera\GeoBasic\Track\Track;
use Caldera\GeoBundle\DistanceCalculator\SimpleDistanceCalculator;
use PHPUnit\Framework\TestCase;

class SimpleDistanceCalculatorTest extends TestCase
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

        $mockedTrack = new Track();
        $mockedTrack->setPolyline('wrzeIuy~{@WdEmBrDmCvAwCuB_D}BaEyBy@yBHoG~@_HrAuItAeG`ByCt@a@`AlAxQv^{@nDwBjB');

        $distance = $distanceCalculator
            ->addTrack($mockedTrack)
            ->calculate();

        $this->assertEquals(1.8518590521829, $distance);
    }
}
