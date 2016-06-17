<?php
namespace Domain\Community\Frontline;

use Application\Frontline\FrontlineScript;
use Domain\Community\Scripts\FeaturesListFrontlineScript;

class ConfigCommunityFrontlineScript implements FrontlineScript
{
    public function tags(): array {
        return [
            FrontlineScript::TAG_GLOBAL
        ];
    }

    public function __invoke(): array {
        return [
            'config' => [
                'community' => [
                    'features' => FeaturesListFrontlineScript::class
                ]
            ]
        ];
    }
}