<?php
namespace Domain\Collection\Collection;

class CollectionTreeFactory
{
    public static function createFromJSON(array $json): CollectionTree
    {
        $tree = new CollectionTree();

        foreach($json as $jsonItem) {
            $item = new CollectionItem($jsonItem['collection_id'], $jsonItem['position']);

            if(is_array($jsonItem['sub']) && count($jsonItem['sub']) > 0) {
                $item->replaceSub(self::createFromJSON($jsonItem['sub']));
            }
        }

        return $tree;
    }
}