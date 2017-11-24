<?php

namespace Caldera\GeoBundle\PositionList;

use Caldera\GeoBundle\EntityInterface\PositionInterface;

interface PositionListInterface
{
    public function getStartDateTime(): \DateTime;

    public function getEndDateTime(): \DateTime;

    public function count(): int;

    public function getLatitude(int $n): float;

    public function getLongitude(int $n): float;

    public function getElevation(int $n): float;

    public function getDateTime(int $n): \DateTime;

    public function get(int $n): ?PositionInterface;

    public function pop(): ?PositionInterface;

    public function push(PositionInterface $position): PositionListInterface;

    public function unshift(): ?PositionInterface;

    public function shift(PositionInterface $position): PositionListInterface;

    public function add(PositionInterface $position): PositionListInterface;

    public function remove(int $n): ?PositionListInterface;
}