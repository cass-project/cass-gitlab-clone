<?php
namespace Domain\Community\Feature\Features;

use Domain\Community\Entity\Community;

final class BoardsFeature extends AbstractFeature
{
    const FEATURE_CODE = 'boards';

    public static function getCode(): string
    {
        return self::FEATURE_CODE;
    }

    public function isProductionReady(): bool
    {
        return false;
    }

    public function isDevelopmentReady(): bool
    {
        return false;
    }

    public function activate(Community $community)
    {
        return true;
    }

    public function deactivate(Community $community)
    {
        return true;
    }
}