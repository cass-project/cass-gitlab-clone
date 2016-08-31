<?php
namespace Domain\Account\Middleware;

use ZEA2\Platform\Bundles\REST\Response\GenericResponseBuilder;
use CASS\Application\Service\CommandService;
use Domain\Account\Exception\AccountNotFoundException;
use Domain\Account\Middleware\Command\CancelDeleteRequestCommand;
use Domain\Account\Middleware\Command\ChangePasswordCommand;
use Domain\Account\Middleware\Command\DeleteRequestCommand;
use Domain\Account\Middleware\Command\GetCurrentAccountCommand;
use Domain\Account\Middleware\Command\SwitchToProfileCommand;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class AccountMiddleware implements MiddlewareInterface
{
    /** @var CommandService */
    private $commandService;

    public function __construct(CommandService $commandService)
    {
        $this->commandService = $commandService;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $responseBuilder = new GenericResponseBuilder($response);

        $resolver = $this->commandService->createResolverBuilder()
            ->attachDirect('change-password', ChangePasswordCommand::class)
            ->attachDirect('request-delete', DeleteRequestCommand::class)
            ->attachDirect('cancel-request-delete', CancelDeleteRequestCommand::class)
            ->attachDirect('switch', SwitchToProfileCommand::class)
            ->attachDirect('current', GetCurrentAccountCommand::class)
            ->resolve($request);
        try {
            return $resolver->run($request, $responseBuilder);
        } catch (AccountNotFoundException $e) {
            return $responseBuilder->setStatusNotFound()
                ->build();
        }
    }
}