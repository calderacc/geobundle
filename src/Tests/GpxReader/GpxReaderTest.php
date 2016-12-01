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

    public function testStartDateTime()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $creationDateTime = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->getStartDateTime();

        $this->assertEquals(new \DateTime('2016-11-25 15:39:38'), $creationDateTime);
    }

    public function testEndDateTime()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $creationDateTime = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->getEndDateTime();

        $this->assertEquals(new \DateTime('2016-11-25 15:49:42'), $creationDateTime);
    }

    public function testCountPositions()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $countPoints = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->countPositions();

        $this->assertEquals(363, $countPoints);
    }
}