<?php
namespace ThemeEditor\Middleware\Command;

use Psr\Http\Message\ServerRequestInterface;
use ThemeEditor\Middleware\Request\DeleteThemeRequest;

class DeleteThemeCommand extends Command
{
    public function run(ServerRequestInterface $request)
    {
        $themeEditorService = $this->getThemeEditorService();
        $themeEditorService->delete(DeleteThemeRequest::factory($request));

        return [
            'success' => true
        ];
    }
}