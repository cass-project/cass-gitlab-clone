<?php
namespace CASS\Domain\Bundles\ProfileCommunities\Middleware\Command;

use ZEA2\Platform\Bundles\REST\Response\ResponseBuilder;
use CASS\Domain\Bundles\Profile\Exception\ProfileNotFoundException;
use CASS\Domain\Bundles\ProfileCommunities\Exception\AlreadyJoinedException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JoinCommand extends Command
{
    public function run(ServerRequestInterface $request, ResponseBuilder $responseBuilder): ResponseInterface
    {
        try {
            $profileId = $request->getAttribute('profileId');
            $communitySID = $request->getAttribute('communitySID');

            $eq = $this->profileCommunitiesService->joinToCommunity(
                $this->currentAccountService->getCurrentAccount()->getProfileWithId($profileId)->getId(),
                $communitySID
            );

            $responseBuilder
                ->setStatusSuccess()
                ->setJson([
                    'entity' => $eq->toJSON()
                ]);
        }catch(AlreadyJoinedException $e) {
            $responseBuilder
                ->setError($e)
                ->setStatusConflict();
        }catch(ProfileNotFoundException $e) {
            $responseBuilder
                ->setError($e)
                ->setStatusNotAllowed();
        }

        return $responseBuilder->build();
    }
}