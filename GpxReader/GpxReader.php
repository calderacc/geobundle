<?php

namespace Caldera\Bundle\CriticalmassCoreBundle\Gps\GpxReader;

use Caldera\Bundle\CalderaBundle\Entity\Position;
use Caldera\Bundle\CriticalmassCoreBundle\Gps\BoundingBox;
use Caldera\Bundle\CriticalmassCoreBundle\Gps\Coord;
use Caldera\Bundle\CriticalmassCoreBundle\Gps\GpxReader\GpxCoordLoop\GpxCoordLoop;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class GpxReader {
    protected $path;
    protected $rawFileContent;
    protected $simpleXml;
    protected $uploaderHelper;
    protected $rootDirectory;

    /** @var \DateTimeZone */
    protected $dateTimeZone = null;

    public function __construct(UploaderHelper $uploaderHelper, $rootDirectory)
    {
        $this->uploaderHelper = $uploaderHelper;
        $this->rootDirectory = $rootDirectory.'/../web';
    }

    public function setDateTimeZone(\DateTimeZone $dateTimeZone = null)
    {
        $this->dateTimeZone = $dateTimeZone;

        return $this;
    }
    
    public function loadFile($path)
    {
        $this->path = $path;
        $this->rawFileContent = file_get_contents($this->rootDirectory.$path);
        $result = true;
        
        try {
            $this->simpleXml = new \SimpleXMLElement($this->rawFileContent);
        } catch (\Exception $e) {
            $result = false;
        }

        return $result;
    }
    
    public function loadString($content)
    {
        $this->rawFileContent = $content;

        $this->simpleXml = new \SimpleXMLElement($this->rawFileContent);
    }

    public function getCreationDateTime()
    {
        return new \DateTime($this->simpleXml->metadata->time);
    }

    public function getStartDateTime()
    {
        return new \DateTime($this->simpleXml->trk->trkseg->trkpt[0]->time);
    }

    public function getEndDateTime()
    {
        return new \DateTime($this->simpleXml->trk->trkseg->trkpt[count($this->simpleXml->trk->trkseg->trkpt) - 1]->time);
    }

    public function countPoints()
    {
        return count($this->simpleXml->trk->trkseg->trkpt);
    }

    public function getMd5Hash()
    {
        return md5($this->rawFileContent);
    }

    public function getFileContent()
    {
        return $this->rawFileContent;
    }

    public function getPoint($n)
    {
        return $this->simpleXml->trk->trkseg->trkpt[$n];
    }

    public function getLatitudeOfPoint($n)
    {
        return (double) $this->simpleXml->trk->trkseg->trkpt[$n]['lat'];
    }

    public function getLongitudeOfPoint($n)
    {
        return (double) $this->simpleXml->trk->trkseg->trkpt[$n]['lon'];
    }

    public function getTimestampOfPoint($n)
    {
        return $this->simpleXml->trk->trkseg->trkpt[$n]->time;
    }
    
    public function getDateTimeOfPoint($n)
    {
        return new \DateTime($this->getTimestampOfPoint($n));
    }

    public function getTimeOfPoint($n)
    {
        return $this->simpleXml->trk->trkseg->trkpt[$n]->time;
    }

    public function getRootNode()
    {
        return $this->simpleXml;
    }

    public function generateJsonArray()
    {
        $result = array();

        $counter = 0;

        foreach ($this->simpleXml->trk->trkseg->trkpt as $point)
        {
            $result[] = array('lat' => (float) $point['lat'], 'lng' => (float) $point['lon']);//'['.$point['lat'].','.$point['lon'].']';

            ++$counter;

            if ($counter > 20)
            {
                break;
            }
        }

        return $result;
    }

    public function generateJsonDateTimeArray($skip = 0)
    {
        $result = '[';

        $first = true;
        $counter = 0;

        foreach ($this->simpleXml->trk->trkseg->trkpt as $point)
        {
            if ($counter == $skip) {
                if (!$first)
                {
                    $result .= ', ';
                }
                
                $result .= '{ "dateTime": "'.(new \DateTime($point->time))->format('U').'", "lat": "'.((float)$point['lat']).'", "lng": "'.((float)$point['lon']).'" }';
                
                $counter = 0;
                $first = false;
            }
            else
            {
                ++$counter;
            }
        }

        $result .= ']';
        
        return $result;
    }

    /**
     * The earth is flat, stupid. As we struggle with PHP and it’s acos calculations we assume the earth to be flat, so
     * we can use Pythagoras here. As we have only small distances about twenty or thirty kilometres, this works well
     * enough. This calculation will fail with wrong distances when a Critical Mass rides from Paris to Berlin or does
     * even larger distances.
     *
     * Don’t show this your kids.
     */
    public function calculateDistance()
    {
        $distance = (float) 0.0;

        $index = 1;

        $firstCoord = $this->simpleXml->trk->trkseg->trkpt[0];

        while ($index < $this->countPoints())
        {
            $secondCoord = $this->simpleXml->trk->trkseg->trkpt[$index];
            
            $dx = 71.5 * ((float) $firstCoord['lon'] - (float) $secondCoord['lon']);
            $dy = 111.3 * ((float) $firstCoord['lat'] - (float) $secondCoord['lat']);

            $way = (float) sqrt($dx * $dx + $dy * $dy);

            $secondTime = new \DateTime($secondCoord->time);
            $firstTime = new \DateTime($firstCoord->time);

            $timeInterval = $secondTime->diff($firstTime);
            $time = $timeInterval->format('%s');
            $time += 0.001;

            $velocity = $way * 1000 / $time;

            if ($velocity > 4.5) {
                $distance += $way;
            }

            $firstCoord = $secondCoord;

            ++$index;
        }

        return (float) round($distance, 2);
    }
    
    public function findCoordNearDateTime(\DateTime $dateTime)
    {
        $gcl = new GpxCoordLoop($this);
        $gcl->setDateTimeZone($this->dateTimeZone);

        $result = $gcl->execute($dateTime);

        return [
            'latitude' => $this->getLatitudeOfPoint($result),
            'longitude' => $this->getLongitudeOfPoint($result)
            ];
    }

    public function calculateDuration()
    {
        $diff = $this->getEndDateTime()->diff($this->getStartDateTime());

        return $diff->format('%h.%i');
    }

    public function getAverageVelocity()
    {
        $diff = $this->getEndDateTime()->diff($this->getStartDateTime());

        $minutes = $diff->h * 60.0 + $diff->i;
        $hours = $minutes/60.0;

        if ($hours > 0) {
            return $this->calculateDistance() / $hours;
        } else {
            return 0;
        }
    }

    public function getAsPositionArray()
    {
        $positionArray = [];

        foreach ($this->simpleXml->trk->trkseg->trkpt as $point)
        {
            $position = new Position();
            $dateTime = new \DateTime($point->time);

            $position->setLatitude((float) $point['lat']);
            $position->setLongitude((float) $point['lon']);
            $position->setTimestamp($dateTime->getTimestamp());
            $position->setCreationDateTime($dateTime);

            $positionArray[] = $position;
        }

        return $positionArray;
    }
    
    public function getBoundingBoxes()
    {
        $firstCoord = $this->simpleXml->trk->trkseg->trkpt[0];

        $north = (float) $firstCoord['lat'];
        $east = (float) $firstCoord['lon'];
        $south = (float) $firstCoord['lat'];
        $west = (float) $firstCoord['lon'];

        $index = 1;
        
        while ($index < $this->countPoints()) {
            $coord = $this->simpleXml->trk->trkseg->trkpt[$index];

            if ($north < (float) $coord['lat']) {
                $north = (float) $coord['lat'];
            }

            if ($west > (float) $coord['lon']) {
                $west = (float) $coord['lon'];
            }

            if ($south > (float) $coord['lat']) {
                $south = (float) $coord['lat'];
            }

            if ($east < (float) $coord['lon']) {
                $east = (float) $coord['lon'];
            }

            ++$index;
        }

        $northWest = new Coord($north, $west);
        $southEast = new Coord($south, $east);

        return new BoundingBox($northWest, $southEast);
    }
}