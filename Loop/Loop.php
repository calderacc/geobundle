<?php

namespace Caldera\GeoBundle\Loop;

use Caldera\GeoBundle\GpxReader\GpxReader;

class Loop
{
    /** @var GpxReader $gpxReader */
    protected $gpxReader;

    /** @var int $startIndex */
    protected $startIndex = 0;

    /** @var int $endIndex */
    protected $endIndex = 0;

    /** @var \DateTimeZone */
    protected $dateTimeZone = null;

    public function __construct(GpxReader $gpxReader, \DateTimeZone $dateTimeZone = null)
    {
        $this->gpxReader = $gpxReader;
        $this->endIndex = $this->gpxReader->countPoints();
        $this->dateTimeZone = $dateTimeZone;
    }

    public function setDateTimeZone(\DateTimeZone $dateTimeZone): Loop
    {
        $this->dateTimeZone = $dateTimeZone;

        return $this;
    }

    public function searchIndexForDateTime(\DateTime $dateTime): int
    {
        $found = false;

        while (!$found) {
            $mid = $this->startIndex + (int)floor(($this->endIndex - $this->startIndex) / 2);

            $midDateTime = $this->gpxReader->getDateTimeOfPoint($mid);

            if ($this->dateTimeZone) {
                $midDateTime->setTimezone($this->dateTimeZone);
            }

            if ($midDateTime->format('Y-m-d-H-i-s') < $dateTime->format('Y-m-d-H-i-s')) {
                $this->startIndex = $mid;
            } elseif ($midDateTime->format('Y-m-d-H-i-s') > $dateTime->format('Y-m-d-H-i-s')) {
                $this->endIndex = $mid;
            } else {
                return $mid;
            }

            if ($this->endIndex - $this->startIndex < 2) {
                return $mid;
            }
        }
    }

    public function searchPointForDateTime(\DateTime $dateTime): \SimpleXMLElement
    {
        $index = $this->searchIndexForDateTime($dateTime);

        return $this->gpxReader->getPoint($index);
    }
}
