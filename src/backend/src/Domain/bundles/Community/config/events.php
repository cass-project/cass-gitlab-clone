<?php
namespace CASS\Domain\Bundles\Community;


use CASS\Domain\Bundles\Community\Events\DashboardCommunityCollectionEvents;
use CASS\Domain\Bundles\Community\Events\LinkCollectionEvents;
use Evenement\EventEmitterInterface;

return function(EventEmitterInterface $globalEmitter): array
{
    return [
        LinkCollectionEvents::class,
        DashboardCommunityCollectionEvents::class,
    ];
};