<?php
namespace Domain\Profile\Middleware\Command;

use Application\Exception\BadCommandCallException;
use Application\REST\Response\ResponseBuilder;
use Domain\Profile\Exception\MaxProfilesReachedException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateCommand extends Command
{
    public function run(ServerRequestInterface $request, ResponseBuilder $responseBuilder): Response {
        $account = $this->currentAccountService->getCurrentAccount();

        try {
            $profile = $this->profileService->createProfileForAccount($account);

            return $responseBuilder
                ->setStatusSuccess()
                ->setJson([
                    'entity' => $profile->toJSON()
                ])
                ->build();
        }catch(MaxProfilesReachedException $e){
            throw new BadCommandCallException($e->getMessage());
        }
    }
}