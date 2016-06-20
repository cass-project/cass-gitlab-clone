<?php
namespace Domain\Collection\Image;

use Domain\Avatar\Entity\ImageEntity;
use Domain\Avatar\Image\Image;
use Domain\Avatar\Strategy\SquareAvatarStrategy;
use Domain\Collection\Entity\Collection;
use League\Flysystem\FilesystemInterface;

final class CollectionImageStrategy extends SquareAvatarStrategy
{
    const DEFAULT_IMAGE_PUBLIC_PATH = '/dist/assets/collection/collection-default-avatar.png';
    const DEFAULT_IMAGE_STORAGE_DIR = __DIR__.'/../../../../../../../www/app/dist/assets/collection/collection-default-avatar.png';

    /** @var Collection */
    private $collection;

    /** @var FilesystemInterface */
    private $fileSystem;

    public function __construct(Collection $collection, FilesystemInterface $fileSystem)
    {
        $this->collection = $collection;
        $this->fileSystem = $fileSystem;
    }

    public function getEntity(): ImageEntity
    {
        return $this->collection;
    }

    public function getEntityId(): string
    {
        return $this->collection->getSID();
    }

    public function getLetter(): string
    {
        return substr($this->collection->getTitle(), 0, 1);
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->fileSystem;
    }

    public function getPublicPath(): string
    {
        return '/dist/assets/collection/by-sid/avatar';
    }

    public function getDefaultImage(): Image
    {
        return new Image(self::DEFAULT_IMAGE_STORAGE_DIR, self::DEFAULT_IMAGE_PUBLIC_PATH);
    }
}