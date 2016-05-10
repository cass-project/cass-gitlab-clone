<?php
namespace Domain\Community\Tests;

use Application\PHPUnit\RESTRequest\Request;
use Application\Util\Definitions\Point;
use Domain\Account\Tests\Fixtures\DemoAccountFixture;
use Domain\Community\Tests\Fixtures\SampleCommunitiesFixture;
use Domain\Profile\Tests\Fixtures\DemoProfileFixture;
use Application\PHPUnit\TestCase\MiddlewareTestCase;
use Domain\Theme\Entity\Theme;
use Domain\Theme\Tests\Fixtures\SampleThemesFixture;
use Zend\Diactoros\UploadedFile;

/**
 * @backupGlobals disabled
 */
class CommunityAPITests extends MiddlewareTestCase
{
    protected function getFixtures(): array
    {
        return [
            new DemoAccountFixture(),
            new DemoProfileFixture(),
            new SampleThemesFixture(),
        ];
    }

    public function testCreateCommunity()
    {
        $theme = SampleThemesFixture::getTheme(1);
        /** @var Theme $theme */
        $json = [
            'title' => 'Community 1',
            'description' => 'My Community 1',
            'theme_id' => $theme->getId()
        ];

        $this->requestCreateCommunity($json)
            ->auth(DemoAccountFixture::getAccount()->getAPIKey())
            ->execute()
            ->expectJSONContentType()
            ->expectStatusCode(200)
            ->expectJSONBody([
                'success' => true,
                'entity' => array_merge_recursive([
                    'id' => $this->expectId()
                ], $json)
            ]);
    }

    public function testCreateCommunity403()
    {
        $theme = SampleThemesFixture::getTheme(1);
        /** @var Theme $theme */
        $json = [
            'title' => 'Community 1',
            'description' => 'My Community 1',
            'theme_id' => $theme->getId()
        ];

        $this->requestCreateCommunity($json)
            ->execute()
            ->expectJSONContentType()
            ->expectStatusCode(403)
            ->expectJSONError();
    }

    public function testEditCommunity()
    {
        $this->upFixture(new SampleCommunitiesFixture());

        $sampleCommunity = SampleCommunitiesFixture::getCommunity(2);
        $moveToTheme = SampleThemesFixture::getTheme(5);

        $this->requestEditCommunity($sampleCommunity->getId(), [
            'title' => '* title_edited',
            'description' => '* description_edited',
            'theme_id' => $moveToTheme->getId()
        ])->auth(DemoAccountFixture::getAccount()->getAPIKey())->execute()
            ->expectJSONContentType()
            ->expectStatusCode(200)
            ->expectJSONBody([
                'success' => true,
                'entity' => [
                    'title' => '* title_edited',
                    'description' => '* description_edited',
                    'theme_id' => $moveToTheme->getId()
                ]
            ]);
    }

    public function testCommunityGetById()
    {
        $this->upFixture(new SampleCommunitiesFixture());

        $sampleCommunity = SampleCommunitiesFixture::getCommunity(2);

        $this->requestGetCommunityById($sampleCommunity->getId())
            ->execute()
            ->expectStatusCode(200)
            ->expectJSONContentType()
            ->expectJSONBody([
                'success' => true,
                'entity' => [
                    'title' => $sampleCommunity->getTitle(),
                    'description' => $sampleCommunity->getDescription(),
                    'theme_id' => $sampleCommunity->getTheme()->getId()
                ]
        ]);
    }

    public function testCommunityGetByIdNotFound()
    {
        $this->upFixture(new SampleCommunitiesFixture());

        $this->requestGetCommunityById(999999)
            ->execute()
            ->expectStatusCode(404)
            ->expectJSONContentType()
            ->expectJSONError();
    }

    public function testUploadImage()
    {
        $this->upFixture(new SampleCommunitiesFixture());

        $sampleCommunity = SampleCommunitiesFixture::getCommunity(2);

        $this->requestUploadImage($sampleCommunity->getId(), new Point(0, 0), new Point(200, 200))
            ->auth(DemoAccountFixture::getAccount()->getAPIKey())
            ->execute()
            ->expectJSONContentType()
            ->expectStatusCode(200)
            ->expectJSONBody([
                'success' => true,
                'image' => [
                    'public_path' => $this->expectString()
                ]
            ]);
    }

    public function testUploadImageTooBig()
    {
        $this->upFixture(new SampleCommunitiesFixture());

        $sampleCommunity = SampleCommunitiesFixture::getCommunity(2);

        $this->requestUploadImage($sampleCommunity->getId(), new Point(0, 0), new Point(9999, 9999))
            ->auth(DemoAccountFixture::getAccount()->getAPIKey())
            ->execute()
            ->expectJSONContentType()
            ->expectStatusCode(422)
            ->expectJSONError();
    }

    private function requestCreateCommunity(array $json): Request
    {
        return $this->request('PUT', '/protected/community/create')
            ->setParameters($json);
    }

    private function requestEditCommunity(int $communityId, array $json)
    {
        return $this->request('POST', sprintf('/protected/community/%d/edit', $communityId))
            ->setParameters($json);
    }

    private function requestUploadImage(int $communityId, Point $start, Point $end): Request
    {
        $uri = "/protected/community/{$communityId}/image-upload/crop-start/{$start->getX()}/{$start->getY()}/crop-end/{$end->getX()}/{$end->getY()}/";
        $fileName = __DIR__ . '/resources/grid-example.png';

        return $this->request('POST', $uri)
            ->setUploadedFiles([
                'file' => new UploadedFile($fileName, filesize($fileName), 0)
            ]);
    }

    private function requestGetCommunityById(int $communityId): Request
    {
        return $this->request('GET', sprintf('/community/%d/get', $communityId));
    }
}