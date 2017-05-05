<?php

namespace Caldera\GeoBundle\Test\DistanceCalculator;

use Caldera\GeoBundle\GpxWriter\GpxWriter;

class GpxWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testGpxWriter1()
    {
        $gpxWriter = new GpxWriter();

        $gpxWriter->generateGpxContent();
    }
}
