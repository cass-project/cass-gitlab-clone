<?php
namespace CASS\Domain\Bundles\ProfileCommunities\Tests\Fixtures;

use ZEA2\Platform\Bundles\PHPUnit\Fixture;
use Doctrine\ORM\EntityManager;
use CASS\Domain\Bundles\Account\Tests\Fixtures\DemoAccountFixture;
use CASS\Domain\Bundles\Community\Tests\Fixtures\SampleCommunitiesFixture;
use CASS\Domain\Bundles\ProfileCommunities\Entity\ProfileCommunityEQ;
use CASS\Domain\Bundles\ProfileCommunities\Service\ProfileCommunitiesService;
use Zend\Expressive\Application;

class SamplePCBookmarksFixture implements Fixture
{
    public static $bookmarks;

    public function up(Application $app, EntityManager $em)
    {
        $profileId = DemoAccountFixture::getSecondAccount()->getCurrentProfile()->getId();
        $service = $app->getContainer()->get(ProfileCommunitiesService::class); /** @var ProfileCommunitiesService $service */

        self::$bookmarks = [
            1 => $service->joinToCommunity($profileId, SampleCommunitiesFixture::getCommunity(1)->getSID()),
            2 => $service->joinToCommunity($profileId, SampleCommunitiesFixture::getCommunity(2)->getSID()),
            3 => $service->joinToCommunity($profileId, SampleCommunitiesFixture::getCommunity(3)->getSID()),
            4 => $service->joinToCommunity($profileId, SampleCommunitiesFixture::getCommunity(4)->getSID()),
            5 => $service->joinToCommunity($profileId, SampleCommunitiesFixture::getCommunity(5)->getSID()),
        ];
    }

    public static function getBookmarks(): array {
        return self::$bookmarks;
    }

    public static function getBookmark(int $index): ProfileCommunityEQ
    {
        return self::$bookmarks[$index];
    }
}