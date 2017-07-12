<?php

namespace Caldera\GeoBundle\GpxReader;

use Caldera\GeoBundle\Entity\Track;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class TrackReader extends GpxReader
{
    /** @var Track $track */
    protected $track;

    /** @var string $positionClass */
    protected $positionClass;

    /** @var string $uploadDestination */
    protected $uploadDestination;

    public function __construct($positionClass, string $uploadDestination)
    {
        parent::__construct($positionClass);

        $this->uploadDestination = $uploadDestination;
    }

    public function loadTrack(Track $track): TrackReader
    {
        $this->track = $track;
        $filename = sprintf('%s%s', $this->uploadDestination, $track->getTrackFilename());

        $this->loadFromFile($filename);

        return $this;
    }

    public function getStartDateTime(): \DateTime
    {
        $startPoint = intval($this->track->getStartPoint());

        return new \DateTime($this->rootNode->trk->trkseg->trkpt[$startPoint]->time);
    }

    public function getEndDateTime(): \DateTime
    {
        $endPoint = intval($this->track->getEndPoint()) - 1;

        return new \DateTime($this->rootNode->trk->trkseg->trkpt[$endPoint]->time);
    }

    public function slicePublicCoords(): array
    {
        // array_slice will not work on xml tree, so we do this manually

        $startPoint = intval($this->track->getStartPoint());
        $endPoint = intval($this->track->getEndPoint());

        $coordArray = [];

        for ($index = $startPoint; $index < $endPoint; ++$index) {
            $coordArray[$index] = [
                $this->getLatitudeOfPoint($index),
                $this->getLongitudeOfPoint($index)
            ];
        }

        return $coordArray;
    }
}
