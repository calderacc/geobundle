<?php

namespace Caldera\GeoBundle\Test\DistanceCalculator;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\GpxReader\GpxReader;
use PHPUnit\Framework\TestCase;

class MultiTrackTest extends TestCase
{
    public function test1()
    {
        $gpxTestFilename = __DIR__.'/../Files/cmhh.gpx';

        $gpxReader = new GpxReader();
        $gpxReader
            ->loadFromFile($gpxTestFilename);

        $this->assertEquals(5218, $gpxReader->countPositions());
    }
}
