<?php

namespace Caldera\GeoBundle\GpxReader;

use Caldera\GeoBundle\Entity\Position;
use Caldera\GeoBundle\EntityInterface\PositionInterface;

class GpxReader
{
    /** @var string $xmlString */
    protected $xmlString;

    /** @var \SimpleXMLElement $rootNode */
    protected $rootNode;

    protected $positionClass;

    public function __construct($positionClass = Position::class)
    {
        $this->positionClass = $positionClass;
    }

    public function loadFromString(string $xmlString): GpxReader
    {
        $this->xmlString = $xmlString;

        $this->rootNode = new \SimpleXMLElement($this->xmlString);

        $this->registerXpathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');

        return $this;
    }

    public function loadFromFile(string $filename): GpxReader
    {
        $this->xmlString = file_get_contents($filename);

        $this->rootNode = new \SimpleXMLElement($this->xmlString);

        $this->registerXpathNamespace('gpx', 'http://www.topografix.com/GPX/1/1');

        return $this;
    }

    public function registerXpathNamespace(string $prefix, string $namespace): GpxReader
    {
        $this->rootNode->registerXPathNamespace($prefix, $namespace);

        return $this;
    }

    public function getCreationDateTime(): \DateTime
    {
        return new \DateTime($this->rootNode->metadata->time);
    }

    public function getStartDateTime(): \DateTime
    {
        return new \DateTime($this->rootNode->trk->trkseg->trkpt[0]->time);
    }

    public function getEndDateTime(): \DateTime
    {
        return new \DateTime($this->rootNode->trk->trkseg->trkpt[count($this->rootNode->trk->trkseg->trkpt) - 1]->time);
    }

    public function getTrackPoints(): array
    {
        return $this->rootNode->xpath('//gpx:trkpt');
    }

    public function countPositions(): int
    {
        return count($this->getTrackPoints());
    }

    public function getLatitudeOfPosition(int $n): float
    {
        return (float) $this->rootNode->trk->trkseg->trkpt[$n]['lat'];
    }

    public function getLongitudeOfPosition(int $n): float
    {
        return (float) $this->rootNode->trk->trkseg->trkpt[$n]['lon'];
    }

    public function getElevationOfPosition($n): float
    {
        return (float) $this->rootNode->trk->trkseg->trkpt[$n]->ele[0];
    }

    public function getDateTimeOfPosition($n): \DateTime
    {
        return new \DateTime($this->rootNode->trk->trkseg->trkpt[$n]->time);
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
