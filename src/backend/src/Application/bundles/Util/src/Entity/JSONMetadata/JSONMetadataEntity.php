<?php
namespace Application\Util\Entity\JSONMetadata;

interface JSONMetadataEntity
{
    public function replaceMetadata(array $metadata);
    public function &getMetadata(): array;
}