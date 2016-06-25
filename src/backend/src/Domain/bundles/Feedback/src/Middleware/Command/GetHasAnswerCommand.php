<?php
namespace Domain\Feedback\Middleware\Command;

use Application\REST\Response\ResponseBuilder;
use Domain\Feedback\Entity\FeedbackResponse;
use Domain\Feedback\Exception\FeedbackNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetHasAnswerCommand extends Command
{
  public function run(ServerRequestInterface $request, ResponseBuilder $responseBuilder): ResponseInterface{
    try{
      $feedbackId = $request->getAttribute('feedbackId');

      $feedbackResponses = $this->feedbackService->getFeedbackResponse($feedbackId);

      return $responseBuilder
        ->setStatusSuccess()
        ->setJson(
          [
            'entities' => array_map(function (FeedbackResponse $feedbackResponse){
                            return $feedbackResponse->toJSON();
                          }, $feedbackResponses)
          ]
        )
        ->build();
    }catch (FeedbackNotFoundException $e){
      return $responseBuilder
        ->setStatusNotFound()
        ->setError($e->getMessage())
        ->build()
        ;
    }
  }
}