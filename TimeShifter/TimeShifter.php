<?php

namespace Caldera\GeoBundle\TimeShifter;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\PositionList\PositionListInterface;

class TimeShifter
{
    /** @var PositionListInterface $positionList */
    protected $positionList;

    public function __construct(PositionListInterface $positionList)
    {
        $this->positionList = $positionList;
    }

    public function shift(\DateInterval $interval): TimeShifter
    {
        for ($i = 0; $i < count($this->positionList); ++$i) {
            /** @var Position $position */
            $position = $this->positionList->get($i);

            $dateTime = new \DateTime(sprintf('@%d', $position->getTimestamp()));
            $dateTime->sub($interval);
            $position->setTimestamp($dateTime->getTimestamp());
        }

        return $this;
    }

    public function getPositionList(): PositionListInterface
    {
        return $this->positionList;
    }
}