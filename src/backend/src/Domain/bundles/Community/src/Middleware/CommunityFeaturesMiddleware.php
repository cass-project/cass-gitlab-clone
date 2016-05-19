<?php
namespace Domain\Community\Middleware;

use Application\REST\Response\GenericResponseBuilder;
use Application\Service\CommandService;
use Domain\Community\Middleware\Command\Feature\ActivateFeatureCommand;
use Domain\Community\Middleware\Command\Feature\DeactivateFeatureCommand;
use Domain\Community\Middleware\Command\Feature\IsFeatureActivatedCommand;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class CommunityFeaturesMiddleware implements MiddlewareInterface
{
    /** @var CommandService */
    private $commandService;

    public function __construct(CommandService $commandService) {
        $this->commandService = $commandService;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $responseBuilder = new GenericResponseBuilder($response);

        $resolver = $this->commandService->createResolverBuilder()
            ->attachDirect('feature', ActivateFeatureCommand::class, 'put')
            ->attachDirect('feature', IsFeatureActivatedCommand::class, 'get')
            ->attachDirect('feature', DeactivateFeatureCommand::class, 'delete')
            ->resolve($request);

        return $resolver->run($request, $responseBuilder);
    }
}