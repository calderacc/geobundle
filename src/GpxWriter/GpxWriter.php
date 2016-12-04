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
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0');

        $writer->setIndent(4);

        $writer->startElement('gpx');

        $this->generateGpxAttributes();

        $writer->startElement('metadata');
        $writer->startElement('time');

        $dateTime = $this->coordList[0]->getDateTime();
        $writer->text($dateTime->format('Y-m-d') . 'T' . $dateTime->format('H:i:s') . 'Z');

        $writer->endElement();
        $writer->endElement();

        $writer->startElement('trk');
        $writer->startElement('trkseg');

        /** @var PositionInterface $position */
        foreach ($this->coordList as $position) {
            $writer->startElement('trkpt');
            $writer->writeAttribute('lat', $position->getLatitude());
            $writer->writeAttribute('lon', $position->getLongitude());

            $writer->startElement('ele');
            $writer->text($position->getAltitude());
            $writer->endElement();

            $writer->startElement('time');

            $dateTime = $position->getDateTime();

            $writer->text($dateTime->format('Y-m-d') . 'T' . $dateTime->format('H:i:s') . 'Z');

            $writer->endElement();
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endElement();
        $writer->endElement();
        $writer->endDocument();

        $this->gpxContent = $writer->outputMemory(true);
    }

    protected function generateGpxAttributes(): GpxWriter
    {
        foreach ($this->gpxAttributes as $attributeName => $attributeValue) {
            $this->writer->writeAttribute($attributeName, $attributeValue);
        }

        return $this;
    }

    public function getGpxContent(): string
    {
        return $this->gpxContent;
    }
} 