<?php
namespace Domain\Collection\Repository;

use Application\Exception\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Domain\Collection\Entity\Collection;
use Domain\Collection\Parameters\CreateCollectionParameters;
use Domain\Theme\Entity\Theme;

class CollectionRepository extends EntityRepository
{
    public function createCollection(string $ownerSID, CreateCollectionParameters $parameters): Collection
    {
        $em = $this->getEntityManager();

        $collection = new Collection(
            $ownerSID,
            $parameters->getTitle(),
            $parameters->getDescription(),
            $parameters->hasThemeId() ? $em->getReference(Theme::class, $parameters->getThemeId()) : null
        );

        $em->persist($collection);
        $em->flush($collection);

        return $collection;
    }

    public function getCollectionById(int $collectionId): Collection
    {
        $result = $this->find($collectionId);

        if($result === null) {
            throw new EntityNotFoundException(sprintf('Collection with ID `%d` not found', $collectionId));
        }

        return $result;
    }

    public function deleteCollection(int $collectionId)
    {
        $collection = $this->getCollectionById($collectionId);

        $this->getEntityManager()->remove($collection);
        $this->getEntityManager()->flush($collection);
    }
}