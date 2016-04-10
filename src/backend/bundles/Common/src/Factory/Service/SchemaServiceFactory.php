<?php
namespace Common\Factory\Service;

use Common\Bootstrap\Bundle\BundleService;
use Common\Service\SchemaService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SchemaServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $bundleService = $container->get(BundleService::class); /** @var BundleService $bundleService */

        return new SchemaService($bundleService);
    }
}