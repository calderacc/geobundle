<?php

namespace Caldera\GeoBundle\EntityInterface;

interface TrackInterface
{
    public function setPolyline(string $polyline): TrackInterface;
    public function getPolyline(): string;
}