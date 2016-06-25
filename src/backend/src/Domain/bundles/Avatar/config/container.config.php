<?php
namespace Domain\Colors;

use function DI\object;
use function DI\factory;
use function DI\get;

use DI\Container;
use Application\Service\BundleService;
use Domain\Avatar\Service\AvatarService;
use Domain\Avatar\Service\Strategy\FileAvatarStrategy;
use Domain\Avatar\Service\Strategy\MockAvatarStrategy;
use Domain\DomainBundle;

$configDefault = [
    'php-di' => [
        AvatarService::class => object()->constructorParameter('strategy', get(FileAvatarStrategy::class))
    ]
];

$configMock = [
    'php-di' => [
        AvatarService::class => object()->constructorParameter('strategy', get(MockAvatarStrategy::class))
    ]
];

return [
    'php-di' => [
        FileAvatarStrategy::class => object()->constructorParameter('fontPath', factory(function(Container $container) {
            $domainBundle = $container->get(BundleService::class)->getBundleByName(DomainBundle::class); /** @var DomainBundle $domainBundle */

            return sprintf('%s/fonts/Roboto/Roboto-Medium.ttf', $domainBundle->getResourcesDir());
        }))
    ],
    'env' => [
        'development' => $configDefault,
        'production' => $configDefault,
        'stage' => $configDefault,
        'test' => $configMock,
    ]
];