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

        return $this;
    }

    public function loadFromFile(string $filename): GpxReader
    {
        $this->xmlString = file_get_contents($filename);

        $this->rootNode = new \SimpleXMLElement($this->xmlString);

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

    public function countPositions(): int
    {
        return count($this->rootNode->trk->trkseg->trkpt);
    }
}