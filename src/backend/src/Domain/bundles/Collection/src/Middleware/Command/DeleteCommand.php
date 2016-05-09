<?php
namespace Domain\Collection\Middleware\Command;

use Domain\Collection\Middleware\Request\CollectionDeleteRequest;
use Psr\Http\Message\ServerRequestInterface;

class DeleteCommand extends Command
{
    public function run(ServerRequestInterface $request)
    {
        $deleteRequest = new CollectionDeleteRequest($request);

        $this->getCollectionService()->delete($deleteRequest->getParameters());
        return [];
    }
}