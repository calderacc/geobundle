<?php

namespace Caldera\GeoBundle\Loop;

use Caldera\GeoBundle\GpxReader\TrackReader;

class TrackLoop extends Loop
{
    /** @var TrackReader $gpxReader */
    protected $trackReader;

    public function __construct(TrackReader $trackReader)
    {
        $this->trackReader = $trackReader;
    }

    public function loadTrackFile(string $filename): TrackLoop
    {
        $this->positionList = $this
            ->trackReader
            ->loadFromFile($filename)
            ->createPositionList()
        ;

        return $this;
    }
}
