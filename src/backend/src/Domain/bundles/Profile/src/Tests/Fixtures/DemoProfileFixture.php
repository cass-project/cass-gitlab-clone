<?php
namespace CASS\Domain\Bundles\Profile\Tests\Fixtures;

use ZEA2\Platform\Bundles\PHPUnit\Fixture;
use Doctrine\ORM\EntityManager;
use CASS\Domain\Bundles\Account\Tests\Fixtures\DemoAccountFixture;
use CASS\Domain\Bundles\Profile\Entity\Profile;
use CASS\Domain\Bundles\Profile\Entity\Profile\Greetings;
use Zend\Expressive\Application;

class DemoProfileFixture implements Fixture
{
    /** @var Profile */
    private static $profile;

    const DEFAULTS = [
        'gender' => Profile\Gender\GenderMale::STRING_CODE
    ];

    public function up(Application $app, EntityManager $em)
    {
        self::$profile = DemoAccountFixture::getAccount()->getProfiles()->first();
    }

    public static function getProfile(): Profile
    {
        return self::$profile;
    }
}