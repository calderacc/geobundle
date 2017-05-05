<?php

namespace Caldera\GeoBundle\GpxReader;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\EntityInterface\PositionInterface;

class GpxReader
{
    /** @var \SimpleXMLElement $rootNode */
    protected $rootNode;

    protected $positionClass;

    /** @var \SimpleXMLElement[]  $trackPointList */
    protected $trackPointList = [];

    public function __construct($positionClass = Position::class)
    {
        $this->positionClass = $positionClass;
    }

    public function loadFromString(string $gpxString): GpxReader
    {
        $this->prepareGpx($gpxString);

        return $this;
    }

    public function loadFromFile(string $filename): GpxReader
    {
        $gpxString = file_get_contents($filename);

        $this->prepareGpx($gpxString);

        return $this;
    }

    protected function prepareGpx(string $xmlString): GpxReader
    {
        $this->rootNode = new \SimpleXMLElement($xmlString);

        $this->registerXpathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');

        $this->createTrackPointList();

        return $this;
    }

    protected function registerXpathNamespace(string $prefix, string $namespace): GpxReader
    {
        $this->rootNode->registerXPathNamespace($prefix, $namespace);

        return $this;
    }

    protected function createTrackPointList(): GpxReader
    {
        $this->trackPointList = $this->rootNode->xpath('//gpx:trkpt');

        return $this;
    }

    public function getCreationDateTime(): \DateTime
    {
        return new \DateTime($this->rootNode->metadata->time);
    }

    public function getStartDateTime(): \DateTime
    {
        return new \DateTime($this->trackPointList[0]->time);
    }

    public function getEndDateTime(): \DateTime
    {
        $lastTrackPointNumber = count($this->rootNode->trk->trkseg->trkpt) - 1;

        return new \DateTime($this->trackPointList[$lastTrackPointNumber]->time);
    }

    public function countPositions(): int
    {
        return count($this->trackPointList);
    }

    public function getLatitudeOfPosition(int $n): float
    {
        return (float) $this->trackPointList[$n]['lat'];
    }

    public function getLongitudeOfPosition(int $n): float
    {
        return (float) $this->trackPointList[$n]['lon'];
    }

    public function getElevationOfPosition($n): float
    {
        return (float) $this->trackPointList[$n]->ele[0];
    }

    public function getDateTimeOfPosition($n): \DateTime
    {
        return new \DateTime($this->trackPointList[$n]->time);
    }

    public function getPosition(int $n): PositionInterface
    {
        /** @var PositionInterface $position */
        $position = new $this->positionClass(
            $this->getLatitudeOfPosition($n),
            $this->getLongitudeOfPosition($n)
        );

        $position
            ->setAltitude($this->getElevationOfPosition($n))
            ->setDateTime($this->getDateTimeOfPosition($n))
        ;

        return $position;
    }
}
