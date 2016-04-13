<?php
namespace Collection\Repository;

use Collection\Entity\Collection;
use Collection\Service\Parameters\CollectionService\CollectionCreateParameters;
use Collection\Service\Parameters\CollectionService\CollectionParemeters;
use Common\Tools\SerialManager\SerialManager;
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

}