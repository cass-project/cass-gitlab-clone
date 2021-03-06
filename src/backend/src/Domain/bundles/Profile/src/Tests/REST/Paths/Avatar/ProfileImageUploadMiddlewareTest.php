<?php
namespace CASS\Domain\Bundles\Profile\Tests\REST\Paths\Avatar;

use CASS\Domain\Bundles\Profile\Tests\REST\Paths\ProfileMiddlewareTestCase;
use CASS\Util\Definitions\Point;
use CASS\Domain\Bundles\Account\Tests\Fixtures\DemoAccountFixture;
use CASS\Domain\Bundles\Profile\Tests\Fixtures\DemoProfileFixture;

/**
 * @backupGlobals disabled
 */
class ProfileImageUploadMiddlewareTest extends ProfileMiddlewareTestCase
{
    public function testUploadImage200()
    {
        $account = DemoAccountFixture::getAccount();
        $profile = DemoProfileFixture::getProfile();

        $p1 = new Point(0, 0);
        $p2 = new Point(150, 150);
        $localFile = __DIR__ . '/../../../Resources/grid-example.png';

        $this->requestUploadImage($profile->getId(), $p1, $p2, $localFile)
            ->execute()
            ->expectAuthError();

        $this->requestUploadImage($profile->getId(), $p1, $p2, $localFile)
            ->auth($account->getAPIKey())
            ->execute()
            ->expectStatusCode(200)
            ->expectJSONContentType()
            ->expectJSONBody([
                'success' => true,
                'image' => $this->expectImageCollection()
            ])
            ->expectJSONBody([
                'image' => [
                    'is_auto_generated' => false
                ]
            ])
        ;
    }
}