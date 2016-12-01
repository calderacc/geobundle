<?php

namespace Caldera\GeoBundle\DistanceCalculator;

use Caldera\GeoBasic\Coord\CoordInterface;
use Caldera\GeoBasic\PolylineConverter\PolylineConverter;
use Caldera\GeoBasic\TrackInterface\TrackInterface;

abstract class AbstractDistanceCalculator implements DistanceCalculatorInterface
{
    /** @var array $coordList */
    protected $coordList = [];

    public function __construct()
    {
    }

    public function addCoord(CoordInterface $coord): DistanceCalculatorInterface
    {
        array_push($this->coordList, $coord);

        return $this;
    }

    public function addCoords(array $coordList): DistanceCalculatorInterface
    {
        $this->coordList = array_merge($this->coordList, $coordList);

        return $this;
    }

    public function addTrack(TrackInterface $track): DistanceCalculatorInterface
    {
        $coordList = PolylineConverter::getCoordList($track);

        $this->addCoords($coordList);

        return $this;
    }
}