<?php

namespace Caldera\GeoBundle\GpxReader;

class GpxReader
{
    /** @var string $xmlString */
    protected $xmlString;

    /** @var \SimpleXMLElement $rootNode */
    protected $rootNode;

    public function __construct(string $xmlString)
    {
        $this->xmlString = $xmlString;

        $this->rootNode = new \SimpleXMLElement($this->xmlString);
    }
}