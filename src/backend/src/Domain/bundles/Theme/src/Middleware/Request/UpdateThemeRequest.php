<?php
namespace Domain\Theme\Middleware\Request;

use ZEA2\Platform\Bundles\REST\Service\JSONSchema;
use ZEA2\Platform\Bundles\REST\Request\Params\SchemaParams;
use Domain\Theme\Parameters\UpdateThemeParameters;
use Domain\Theme\ThemeBundle;

class UpdateThemeRequest extends SchemaParams
{
    public function getParameters(): UpdateThemeParameters {
        $data = $this->getData();

        return new UpdateThemeParameters(
            $data['title'],
            $data['description'],
            $data['preview'] ?? null,
            $data['url'] ?? null
        );
    }

    protected function getSchema(): JSONSchema {
        return self::getSchemaService()->getSchema(ThemeBundle::class, './definitions/request/UpdateThemeRequest.yml');
    }
}