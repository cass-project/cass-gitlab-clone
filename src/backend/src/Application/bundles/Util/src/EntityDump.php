<?php
namespace Application\Util;

use Application\Util\JSONSerializable;

class EntityDump
{
    public static function dump(array $entities) {
        return array_map(function(JSONSerializable $entity) {
            return $entity->toJSON();
        }, $entities);
    }
}