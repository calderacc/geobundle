<?php

namespace Caldera\GeoBundle\DistanceCalculator;

use Caldera\GeoBasic\Coord\CoordInterface;

class SimpleDistanceCalculator extends AbstractDistanceCalculator
{
    public function calculate(): float
    {
        $distance = 0.0;

        /** @var CoordInterface $coord1 */
        $coord1 = array_pop($this->coordList);

        /** @var CoordInterface $coord2 */
        while ($coord2 = array_pop($this->coordList)) {
            $dx = 71.5 * ((float) $coord1->getLongitude() - (float) $coord2->getLongitude());
            $dy = 111.3 * ((float) $coord1->getLatitude() - (float) $coord2->getLatitude());

            $distance += sqrt($dx * $dx + $dy * $dy);

            $coord1 = $coord2;
        }

        return $distance;
    }
}