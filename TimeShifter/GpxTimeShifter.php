<?php

namespace Caldera\GeoBundle\TimeShifter;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\Entity\Track;
use Caldera\GeoBundle\GpxReader\GpxReader;

class GpxTimeShifter
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

        return $this;
    }

    public function shift(\DateInterval $interval)
    {
        for ($i = 0; $i < $this->gpxReader->countPoints(); ++$i) {
            /** @var Position $position */
            $position = $this->gpxReader->getPosition($i);

            $dateTime = new \DateTime(sprintf('@%d', $position->getTimestamp()));
            $dateTime->sub($interval);
            $position->setTimestamp($dateTime->getTimestamp());
        }

        return $this;
    }

    public function getGpxReader(): GpxReader
    {
        return $this->gpxReader;
    }
}
