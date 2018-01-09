<?php

namespace Caldera\GeoBundle\Loop;

use Caldera\GeoBundle\GpxReader\GpxReader;

class GpxLoop extends Loop
{
    /** @var GpxReader $gpxReader */
    protected $gpxReader;

    public function __construct(GpxReader $gpxReader)
    {
        $this->gpxReader = $gpxReader;
    }

    public function loadGpxFile(string $filename): GpxLoop
    {
        $this->positionList = $this
            ->gpxReader
            ->loadFromFile($filename)
            ->createPositionList()
        ;

        return $this;
    }
}
