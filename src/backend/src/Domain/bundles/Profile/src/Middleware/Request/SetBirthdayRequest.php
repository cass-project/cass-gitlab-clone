<?php
namespace CASS\Domain\Bundles\Profile\Middleware\Request;

use ZEA2\Platform\Bundles\REST\Service\JSONSchema;
use ZEA2\Platform\Bundles\REST\Request\Params\SchemaParams;
use CASS\Domain\Bundles\Profile\Parameters\SetBirthdayParameters;
use CASS\Domain\Bundles\Profile\ProfileBundle;

class SetBirthdayRequest extends SchemaParams
{
    public function getParameters(): SetBirthdayParameters
    {
        return new SetBirthdayParameters(
            \DateTime::createFromFormat(\DateTime::RFC2822, $this->getData()['date'])
        );
    }

    protected function getSchema(): JSONSchema
    {
        return self::getSchemaService()->getSchema(ProfileBundle::class, './definitions/request/SetBirthdayRequest.yml');
    }
}