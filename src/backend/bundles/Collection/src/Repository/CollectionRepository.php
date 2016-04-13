<?php
namespace Collection\Repository;

use Collection\Entity\Collection;
use Collection\Service\Parameters\CollectionService\CollectionCreateParameters;
use Collection\Service\Parameters\CollectionService\CollectionDeleteParameters;
use Collection\Service\Parameters\CollectionService\CollectionParemeters;
use Collection\Service\Parameters\CollectionService\CollectionUpdateParameters;
use Common\Tools\SerialManager\SerialManager;
use Data\Exception\DataEntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Profile\Entity\Profile;

class CollectionRepository extends EntityRepository
{
    public function create(Profile $profile, CollectionCreateParameters $collectionCreateParameters): Collection
    {
        $collectionEntity = new Collection($profile);

        $this->setupEntity($collectionEntity, $collectionCreateParameters);

        $em = $this->getEntityManager();
        $em->persist($collectionEntity);
        $em->flush();

        return $collectionEntity;
    }

    public function update(CollectionUpdateParameters $collectionUpdateParameters): Collection
    {
        $themeEntity = $this->getCollectionEntity($collectionUpdateParameters->getId());

        $this->setupEntity($themeEntity, $collectionUpdateParameters);

        $em = $this->getEntityManager();
        $em->persist($themeEntity);
        $em->flush();

        return $themeEntity;
    }

    public function delete(CollectionDeleteParameters $collectionDeleteParameters): Collection
    {
        $collectionEntity = $this->getCollectionEntity($collectionDeleteParameters->getCollectionId());

        $parentId = $collectionEntity->hasParent() ? $collectionEntity->getParent()->getId() : null;
        $siblings = new SerialManager($this->getCollectionsWithParent($parentId));

        $siblings->remove($collectionEntity);

        $em = $this->getEntityManager();
        $em->remove($collectionEntity);
        $em->flush();

        return $collectionEntity;
    }

    private function setupEntity(Collection $collectionEntity, CollectionParemeters $collectionParameters)
    {
        $em = $this->getEntityManager();

        $collectionParameters->getTitle()->on([$collectionEntity, "setTitle"]);
        $collectionParameters->getDescription()->on([$collectionEntity, "setDescription"]);

        $collectionParameters->getParentId()->on(function($value) use ($collectionEntity, $em, &$parentId) {
            if($value > 0) {
                $parentId = $value;
                $parent = $em->getReference(Collection::class, $value);
                $collectionEntity->setParent($parent);
            }
        });

        $collectionParameters->getPosition()->on(function($value) use (&$position) {
            $position = $value;
        });

        $insert = $position ? "insertAs" : "insertLast";
        $siblings = new SerialManager($this->getCollectionsWithParent($parentId));
        $siblings->$insert($collectionEntity, $position);
    }

    /**
     * @return Collection[]
     */
    public function getCollectionsWithParent(int $parentId = null)
    {
        if ($parentId) {
            $queryBuilder = $this->createQueryBuilder('c')
                ->andWhere('c.parent = :parent')
                ->setParameter('parent', $parentId);
        } else {
            $queryBuilder = $this->createQueryBuilder('c')
                ->andWhere('c.parent IS NULL');
        }
        return $queryBuilder->getQuery()->getResult();
    }

    public function getCollectionEntity(int $id): Collection
    {
        $collectionEntity = $this->find($id);
        /** @var Collection $collectionEntity */

        if ($collectionEntity === null) {
            throw new DataEntityNotFoundException(sprintf("Theme Entity with ID `%d` not found", $id));
        }
        return $collectionEntity;
    }
}