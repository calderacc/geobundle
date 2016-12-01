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
}