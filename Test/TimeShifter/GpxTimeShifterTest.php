<?php

namespace Caldera\GeoBundle\Test\DistanceCalculator;

use Caldera\GeoBundle\GpxReader\GpxReader;
use Caldera\GeoBundle\TimeShifter\GpxTimeShifter;
use PHPUnit\Framework\TestCase;

class GpxTimeShifterTest extends TestCase
{
    public function testGpxTimeShifter1()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();

        $dateTime = $gpxReader
            ->loadFromFile($gpxTestFilename)
            ->getDateTimeOfPoint(5);

        $this->assertEquals(new \DateTime('2016-11-25 15:40:29'), $dateTime);
    }

    public function testGpxTimeShifter2()
    {
        $gpxTestFilename = __DIR__.'/../Files/bahnhof.gpx';

        $gpxReader = new GpxReader();
        $timeShifter = new GpxTimeShifter($gpxReader);

        $interval = new \DateInterval('PT5M');

        $timeShifter
            ->loadGpxFile($gpxTestFilename)
            ->shift($interval);

        $dateTime = $timeShifter
            ->getGpxReader()
            ->getDateTimeOfPoint(5);

        $this->assertEquals(new \DateTime('2016-11-25 15:40:29'), $dateTime);
    }
}
