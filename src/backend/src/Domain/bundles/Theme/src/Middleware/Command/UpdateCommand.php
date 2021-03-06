<?php
namespace CASS\Domain\Bundles\Theme\Middleware\Command;

use ZEA2\Platform\Bundles\REST\Response\ResponseBuilder;
use CASS\Domain\Bundles\Theme\Exception\ThemeNotFoundException;
use CASS\Domain\Bundles\Theme\Middleware\Request\UpdateThemeRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateCommand extends Command
{
    public function run(ServerRequestInterface $request, ResponseBuilder $responseBuilder): ResponseInterface
    {
        try {
            $parameters = (new UpdateThemeRequest($request))->getParameters();
            $themeEntity = $this->themeService->updateTheme((int) $request->getAttribute('themeId'), $parameters);

            $responseBuilder
                ->setJson([
                    'entity' => $themeEntity->toJSON()
                ])
                ->setStatusSuccess();
        }catch(ThemeNotFoundException $e) {
            $responseBuilder
                ->setError($e)
                ->setStatusNotFound();
        }

        return $responseBuilder->build();
    }
}