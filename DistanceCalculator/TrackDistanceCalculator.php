<?php

namespace Caldera\AppBundle\DistanceCalculator;

use Caldera\GeoBundle\DistanceCalculator\DistanceCalculatorInterface;
use Caldera\GeoBundle\Entity\Track;
use Caldera\GeoBundle\GpxReader\TrackReader;
use Doctrine\Bundle\DoctrineBundle\Registry;

class TrackDistanceCalculator implements DistanceCalculatorInterface
{
    protected $doctrine;

    /** @var TrackReader $trackReader */
    protected $trackReader;

    /** @var Track $track */
    protected $track;

    public function __construct(Registry $doctrine, TrackReader $trackReader)
    {
        $this->doctrine = $doctrine;
        $this->trackReader = $trackReader;
    }

    public function loadTrack(Track $track): TrackDistanceCalculator
    {
        $this->track = $track;
        $this->trackReader->loadTrack($track);

        return $this;
    }

    public function calculate(): float
    {
        $startPoint = intval($this->track->getStartPoint());
        $endPoint = intval($this->track->getEndPoint());
        $distance = (float) 0.0;

        $index = $startPoint + 1;
        $firstCoord = $this->trackReader->getPoint($startPoint);

        while ($index < $endPoint) {
            $secondCoord = $this->trackReader->getPoint($index);

            $dx = 71.5 * ((float)$firstCoord['lon'] - (float)$secondCoord['lon']);
            $dy = 111.3 * ((float)$firstCoord['lat'] - (float)$secondCoord['lat']);

            $way = (float)sqrt($dx * $dx + $dy * $dy);

            $distance += $way;

            ++$index;

            $firstCoord = $secondCoord;
        }

        return (float)round($distance, 2);
    }
}
