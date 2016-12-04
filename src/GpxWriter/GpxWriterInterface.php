<?php

namespace Caldera\GeoBundle\GpxWriter;

interface GpxWriterInterface
{
    public function getGpxContent(): string;

    public function saveGpxContent(string $filename): void;

    public function addGpxAttribute(string $attributeName, string $attributeValue): GpxWriter;

    public function addStandardGpxAttributes(): GpxWriter;

    public function generateGpxContent(): void;
}