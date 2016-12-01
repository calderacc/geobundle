<?php

namespace Caldera\GeoBundle\EntityInterface;

use Caldera\GeoBasic\Coord\CoordInterface;

interface PositionInterface extends CoordInterface
{
    public function setLatitude(float $latitude): PositionInterface;
    public function getLatitude(): float;

    public function setLongitude(float $longitude): PositionInterface;
    public function getLongitude(): float;

    public function setAccuracy(float $accuracy): PositionInterface;
    public function getAccuracy();

    public function setAltitude(float $altitude): PositionInterface;
    public function getAltitude();

    public function setAltitudeAccuracy(float $altitudeAccuracy): PositionInterface;
    public function getAltitudeAccuracy();

    public function setHeading(float $heading): PositionInterface;
    public function getHeading();

    public function setSpeed(float $speed): PositionInterface;
    public function getSpeed();

    public function setTimestamp(int $timestamp): PositionInterface;
    public function getTimestamp();

    public function setCreationDateTime(\DateTime $creationDateTime): PositionInterface;
    public function getCreationDateTime(): \DateTime;
}