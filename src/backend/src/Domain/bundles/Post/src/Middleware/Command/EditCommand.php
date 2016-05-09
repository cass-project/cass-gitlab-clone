<?php
namespace Domain\Post\Middleware\Command;
 
use Domain\Post\Middleware\Request\EditPostRequest;
use Psr\Http\Message\ServerRequestInterface;

class EditCommand extends Command
{
    public function run(ServerRequestInterface $request) {
        $editPostParameters = (new EditPostRequest($request))->getParameters();

        $post = $this->postService->editPost($editPostParameters);

        return [
            'entity' => $post->toJSON()
        ];
    }
}