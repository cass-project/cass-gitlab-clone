<?php
namespace Domain\Feed\Search\Stream\Streams;

use Domain\Feed\Search\Criteria\Criteria\SeekCriteria;
use Domain\Feed\Search\Criteria\Criteria\SortCriteria;
use Domain\Feed\Search\Criteria\Criteria\ThemeIdCriteria;
use Domain\Feed\Search\Criteria\CriteriaManager;
use Domain\Feed\Search\Stream\Stream;
use Domain\Profile\Entity\Profile;
use Domain\Profile\Service\ProfileService;
use MongoDB\BSON\ObjectID;
use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

final class ProfileStream extends Stream
{
    /** @var ProfileService */
    private $profileService;
    
    public function setProfileService(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    
    public function fetch(CriteriaManager $criteriaManager, Collection $collection): array
    {
        $order = 1;
        $filter = [];
        $options = [
            'limit' => self::DEFAULT_LIMIT,
        ];

        $criteriaManager->doWith(SortCriteria::class, function(SortCriteria $criteria) use (&$options, &$order) {
            $order = strtolower($criteria->getOrder()) === 'asc' ? 1 : -1;

            $options['sort'] = [];
            $options['sort'][$criteria->getField()] = $order;
        });

        $criteriaManager->doWith(SeekCriteria::class, function(SeekCriteria $criteria) use (&$options, &$filter, $order) {
            $options['limit'] = $criteria->getLimit();
            $options['skip'] = 0;

            if($criteria->getLastId()) {
                $lastId = new ObjectID($criteria->getLastId());

                if($order === 1) {
                    $filter['_id'] = [
                        '$gt' => $lastId
                    ];
                }else{
                    $filter['_id'] = [
                        '$lt' => $lastId
                    ];
                }
            }
        });

        $criteriaManager->doWith(ThemeIdCriteria::class, function(ThemeIdCriteria $criteria) use (&$filter) {
            $filter[sprintf('theme_ids.%s', (string) $criteria->getThemeId())] = [
                '$exists' => true
            ];
        });

        $cursor = $collection->find($filter, $options)->toArray();

        if(count($cursor)) {
            $profileEntities = $this->profileService->getProfilesByIds(array_map(function(BSONDocument $document) {
                return (int) $document['id'];
            }, $cursor));

            return array_map(function(Profile $profile) {
                return $profile->toJSON();
            }, $profileEntities);
        }else{
            return [];
        }
    }
}