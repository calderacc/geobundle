<?php

namespace Caldera\GeoBundle\DistanceCalculator;

interface DistanceCalculatorInterface
{
    public function calculate(): float;
}
