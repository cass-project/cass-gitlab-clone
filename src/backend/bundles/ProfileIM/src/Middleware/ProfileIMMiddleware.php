<?php
namespace ProfileIM\Middleware;

use Auth\Service\CurrentAccountService;
use Common\REST\GenericRESTResponseBuilder;
use ProfileIM\Exception\SameTargetAndSourceException;
use ProfileIM\Middleware\Command\Command;
use Profile\Service\ProfileService;
use ProfileIM\Service\ProfileIMService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Stratigility\MiddlewareInterface;

class ProfileIMMiddleware implements MiddlewareInterface
{
    /** @var CurrentAccountService */
    private $currentAccountService;

    /** @var ProfileIMService */
    private $profileIMService;

    /** @var  ProfileService */
    private $profileService;

    public function __construct(CurrentAccountService $currentAccountService, ProfileIMService $profileIMService, ProfileService $profileService)
    {
        $this->currentAccountService = $currentAccountService;
        $this->profileIMService = $profileIMService;
        $this->profileService = $profileService;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $responseBuilder = new GenericRESTResponseBuilder($response);
        
        try{
            $command = Command::factory($request, $this->currentAccountService, $this->profileIMService, $this->profileService);
            $result  = $command->run($request);

            $responseBuilder
              ->setStatusSuccess()
              ->setJson($result);
        } catch(SameTargetAndSourceException $e){
            $responseBuilder
              ->setStatusBadRequest()
              ->setError($e);
        }


        return $responseBuilder->build();
    }
}