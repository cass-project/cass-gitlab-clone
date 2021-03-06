<?php
namespace CASS\Domain\Bundles\Theme\Frontline;

use CASS\Application\Bundles\Frontline\FrontlineScript;
use CASS\Domain\Bundles\Theme\Entity\Theme;
use CASS\Domain\Bundles\Theme\Service\ThemeService;

class ThemeScript implements FrontlineScript
{
    /** @var ThemeService */
    private $themeService;

    /** @var string */
    private $wwwStorage;

    public function __construct(ThemeService $themeService, string $wwwStorage)
    {
        $this->themeService = $themeService;
        $this->wwwStorage = $wwwStorage;
    }

    public function __invoke(): array
    {
        return [
            'themes' => $this->buildJSON($this->themeService->getThemesAsTree()),
            'config' => [
                'themes' => [
                    'www' => $this->wwwStorage,
                ],
            ],
        ];
    }

    public function tags(): array
    {
        return [FrontlineScript::TAG_GLOBAL];
    }

    private function buildJSON(array $themes)
    {
        return array_map(function(Theme $theme) {
            $result = $theme->toJSON();
            $result['children'] = $theme->hasChildren() ? $this->buildJSON($theme->getChildren()->toArray()) : [];

            return $result;
        }, $themes);
    }
}