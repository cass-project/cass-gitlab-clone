<?php
namespace Profile\Middleware\Command;

use Profile\Exception\NotOwnProfileException;
use Profile\Middleware\Request\InterestingInRequest;
use Psr\Http\Message\ServerRequestInterface;

class InterestingInPostCommand extends Command
{
    public function run(ServerRequestInterface $request)
    {
        $profileId = (int) $request->getAttribute('profileId');

        if(! $this->validateIsOwnProfile($profileId)) {
            throw new NotOwnProfileException(sprintf('Profile with ID `%s` is not yours', $profileId));
        }
        
        $interestingInRequest = new InterestingInRequest($request);
        $interestingInParameters = $interestingInRequest->getParameters();

        $this->profileService->mergeInterestingInParameters($profileId, $interestingInParameters);
        return ['success'=> TRUE];
    }
}