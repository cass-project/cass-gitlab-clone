<?php
namespace Domain\Theme\Frontline;

use Application\Frontline\FrontlineScript;
use Domain\Theme\Entity\Theme;
use Domain\Theme\Service\ThemeService;

class ThemeScript implements FrontlineScript
{
    /** @var ThemeService */
    private $themeService;

    public function __construct(ThemeService $themeService) {
        $this->themeService = $themeService;
    }

    public function __invoke(): array {
        return [
            'themes' => $this->buildJSON($this->themeService->getThemesAsTree())
        ];
    }

    public function tags(): array {
        return [FrontlineScript::TAG_GLOBAL];
    }

    private function buildJSON(array $themes) {
        return array_map(function(Theme $theme) {
            $result = $theme->toJSON();
            $result['children'] = $theme->hasChildren() ? $this->buildJSON($theme->getChildren()->toArray()) : [];

            return $result;
        }, $themes);
    }
}