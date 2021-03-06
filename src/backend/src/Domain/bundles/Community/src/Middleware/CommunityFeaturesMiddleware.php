<?php
namespace CASS\Domain\Bundles\Community\Middleware;

use CASS\Application\REST\CASSResponseBuilder;
use CASS\Application\Service\CommandService;
use CASS\Domain\Bundles\Community\Middleware\Command\Feature\ActivateFeatureCommand;
use CASS\Domain\Bundles\Community\Middleware\Command\Feature\DeactivateFeatureCommand;
use CASS\Domain\Bundles\Community\Middleware\Command\Feature\IsFeatureActivatedCommand;
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
        $responseBuilder = new CASSResponseBuilder($response);

        $resolver = $this->commandService->createResolverBuilder()
            ->attachDirect('activate', ActivateFeatureCommand::class)
            ->attachDirect('is-activated', IsFeatureActivatedCommand::class)
            ->attachDirect('deactivate', DeactivateFeatureCommand::class)
            ->resolve($request);

        return $resolver->run($request, $responseBuilder);
    }
}