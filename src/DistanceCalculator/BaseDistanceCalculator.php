<?php

namespace Caldera\DistanceCalculator;

use Caldera\GeoBasic\Coord\Coord;

class DistanceCalculator
{
    /** @var array $coordList */
    private $coordList = [];

    public function __construct()
    {
    }

    public function addCoord(Coord $coord): DistanceCalculator
    {
        array_push($this->coordList, $coord);

        return $this;
    }
}