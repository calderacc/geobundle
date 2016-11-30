<?php

namespace Caldera\GeoBundle\DistanceCalculator;

use Caldera\GeoBasic\Coord\CoordInterface;

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
}