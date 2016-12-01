<?php

namespace Caldera\GeoBundle\Tests\DistanceCalculator;

use Caldera\GeoBundle\GpxReader\GpxReader;

class GpxReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testGpxReader1()
    {
        $gpxTestFilename = __DIR__.'/../Files/cmhh.gpx';

        $gpxReader = new GpxReader();
        $gpxReader
            ->loadFromFile($gpxTestFilename);
    }

    public function testCreationDateTime()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $creationDateTime = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->getCreationDateTime();

        $this->assertEquals(new \DateTime('2016-11-25 15:39:38'), $creationDateTime);
    }

    public function testCountPoints()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $countPoints = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->countPoints();

        $this->assertEquals(363, $countPoints);
    }
}