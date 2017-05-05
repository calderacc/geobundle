<?php

namespace Caldera\GeoBundle\DistanceCalculator;

use Caldera\GeoBasic\Coord\CoordInterface;

interface DistanceCalculatorInterface
{
    public function addCoord(CoordInterface $coord): DistanceCalculatorInterface;
    public function calculate(): float;
}