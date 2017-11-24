<?php

namespace Caldera\GeoBundle\TimeShifter;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\Entity\Track;
use Caldera\GeoBundle\GpxReader\GpxReader;

class GpxTimeShifter extends TimeShifter
{
    /** @var GpxReader $gpxReader */
    protected $gpxReader;

    /** @var Track $track */
    protected $track;

    public function __construct(GpxReader $gpxReader)
    {
        $this->gpxReader = $gpxReader;
    }

    public function loadGpxFile(string $filename): GpxTimeShifter
    {
        $this->gpxReader->loadFromFile($filename);

        $this->positionList = $this->gpxReader->createPositionList();

        return $this;
    }

    public function getGpxReader(): GpxReader
    {
        return $this->gpxReader;
    }
}
