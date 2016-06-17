<?php
namespace Domain\Community\Middleware\Request;

use Application\REST\Request\Params\SchemaParams;
use Application\REST\Service\JSONSchema;
use Domain\Community\CommunityBundle;
use Domain\Community\Parameters\EditCommunityParameters;
use Domain\Community\Parameters\SetPublicOptionsParameters;

class SetPublicOptionsRequest extends SchemaParams
{
    public function getParameters(): SetPublicOptionsParameters
    {
        $data = $this->getData();

        return new SetPublicOptionsParameters(
            (bool) $data->public_enabled,
            (bool) $data->moderation_contract
        );
    }

    protected function getSchema(): JSONSchema
    {
        return self::getSchemaService()->getSchema(CommunityBundle::class, './definitions/request/SetPublicOptionsRequest.yml');
    }
}