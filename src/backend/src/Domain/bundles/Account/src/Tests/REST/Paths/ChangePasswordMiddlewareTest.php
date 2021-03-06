<?php
namespace CASS\Domain\Bundles\Account\Tests\REST\Paths;

use CASS\Domain\Bundles\Account\Tests\AccountMiddlewareTestCase;
use CASS\Domain\Bundles\Account\Tests\Fixtures\DemoAccountFixture;

/**
 * @backupGlobals disabled
 */
final class ChangePasswordMiddlewareTest extends AccountMiddlewareTestCase
{
    protected function getFixtures(): array
    {
        return [
            new DemoAccountFixture()
        ];
    }

    public function testChangePassword403()
    {
        $json = [
            'old_password' => DemoAccountFixture::ACCOUNT_PASSWORD,
            'new_password' => 'foobar'
        ];

        $this->requestChangePassword($json)
            ->execute()
            ->expectAuthError();
    }

    public function testChangePassword200()
    {
        $json = [
            'old_password' => DemoAccountFixture::ACCOUNT_PASSWORD,
            'new_password' => 'foobar'
        ];

        $this->requestChangePassword($json)
            ->auth(DemoAccountFixture::getAccount()->getAPIKey())
            ->execute()
            ->expectStatusCode(200)
            ->expectJSONContentType()
            ->expectJSONBody([
                'success' => true,
                'apiKey' => $this->expectString()
            ]);
    }

    public function testChangePassword409()
    {
        $json = [
            'old_password' => 'bar-and-foo',
            'new_password' => 'foobar'
        ];

        $this->requestChangePassword($json)
            ->auth(DemoAccountFixture::getAccount()->getAPIKey())
            ->execute()
            ->expectStatusCode(409)
            ->expectJSONContentType()
            ->expectJSONBody([
                'success' => false,
                'error' => $this->expectString()
            ]);
    }
}