<?php

namespace Caldera\GeoBundle\GpxWriter;

use Caldera\GeoBundle\EntityInterface\PositionInterface;

class GpxWriter
{
    /** @var array $coordList */
    protected $coordList;

    /** @var array $gpxAttributes */
    protected $gpxAttributes = [];

    /** @var string $gpxContent */
    protected $gpxContent = null;

    /** @var \XMLWriter $writer */
    protected $writer;

    public function __construct(array $coordList = [])
    {
        $this->coordList = $coordList;

        $this->writer = new \XMLWriter();
    }

    public function addGpxAttribute(string $attributeName, string $attributeValue): GpxWriter
    {
        $this->gpxAttributes[$attributeName] = $attributeValue;

        return $this;
    }

    public function addStandardGpxAttributes(): GpxWriter
    {
        $this->gpxAttributes['xmlns'] = 'http://www.topografix.com/GPX/1/1';
        $this->gpxAttributes['xmlns:xsi'] = 'http://www.w3.org/2001/XMLSchema-instance';
        $this->gpxAttributes['xsi:schemaLocation'] = 'http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd http://www.garmin.com/xmlschemas/GpxExtensions/v3 http://www.garmin.com/xmlschemas/GpxExtensionsv3.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd';

        return $this;
    }

    public function generateGpxContent(): void
    {
        $this->writer->openMemory();
        $this->writer->startDocument('1.0');

        $this->writer->setIndent(4);

        $this->writer->startElement('gpx');

        $this->generateGpxAttributes();
        $this->generateGpxMetadata();

        $this->writer->startElement('trk');
        $this->writer->startElement('trkseg');

        /** @var PositionInterface $position */
        foreach ($this->coordList as $position) {
            $this->generateGpxPosition($position);
        }

        $this->writer->endElement();
        $this->writer->endElement();
        $this->writer->endElement();
        $this->writer->endDocument();

        $this->gpxContent = $this->writer->outputMemory(true);

        $this->writer->flush();
    }

    protected function generateGpxAttributes(): GpxWriter
    {
        foreach ($this->gpxAttributes as $attributeName => $attributeValue) {
            $this->writer->writeAttribute($attributeName, $attributeValue);
        }

        return $this;
    }

    protected function generateGpxMetadata(): GpxWriter
    {
        $this->writer->startElement('metadata');
        $this->writer->startElement('time');

        /** @var \DateTime $dateTime */
        $dateTime = $this->coordList[0]->getDateTime();
        $this->writer->text($dateTime->format('Y-m-d') . 'T' . $dateTime->format('H:i:s') . 'Z');

        $this->writer->endElement();
        $this->writer->endElement();

        return $this;
    }

    protected function generateGpxPosition(PositionInterface $position): GpxWriter
    {
        $this->writer->startElement('trkpt');
        $this->writer->writeAttribute('lat', $position->getLatitude());
        $this->writer->writeAttribute('lon', $position->getLongitude());

        if ($position->getAltitude()) {
            $this->writer->startElement('ele');
            $this->writer->text($position->getAltitude());
            $this->writer->endElement();
        }

        if ($position->getDateTime()) {
            $this->writer->startElement('time');
            $dateTime = $position->getDateTime();
            $this->writer->text($dateTime->format('Y-m-d') . 'T' . $dateTime->format('H:i:s') . 'Z');
            $this->writer->endElement();
        }

        $this->writer->endElement();

        return $this;
    }

    public function getGpxContent(): string
    {
        return $this->gpxContent;
    }
} 