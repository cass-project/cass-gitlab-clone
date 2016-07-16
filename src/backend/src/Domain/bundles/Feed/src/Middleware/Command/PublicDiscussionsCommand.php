<?php
namespace Domain\Feed\Middleware\Command;

use Application\REST\Response\ResponseBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class PublicDiscussionsCommand extends AbstractCommand
{
    public function run(ServerRequestInterface $request, ResponseBuilder $responseBuilder): ResponseInterface
    {
        $source = $this->sourceFactory->getPublicDiscussionsSource();

        return $this->makeFeed($source, $request, $responseBuilder);
    }
}