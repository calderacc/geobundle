<?php

namespace Caldera\GeoBundle\TimeShifter;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\Entity\Track;
use Caldera\GeoBundle\GpxReader\TrackReader;

class TrackTimeShifter
{
    /** @var TrackReader $trackReader */
    protected $trackReader;

    /** @var Track $track */
    protected $track;

    public function __construct(TrackReader $trackReader)
    {
        $this->trackReader = $trackReader;
    }

    public function loadTrack(Track $track): TrackTimeShifter
    {
        $this->track = $track;

        $this->trackReader->loadTrack($this->track);

        return $this;
    }

    public function shift(\DateInterval $interval)
    {
        for ($i = 0; $i <= $this->trackReader->countPoints(); ++$i) {
            /** @var Position $position */

            $dateTime = new \DateTime(sprintf('@%d', $position->getTimestamp()));
            $dateTime->sub($interval);
            $position->setTimestamp($dateTime->getTimestamp());
        }

        return $this;
    }
}
