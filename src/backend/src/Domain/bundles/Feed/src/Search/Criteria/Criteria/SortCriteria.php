<?php
namespace CASS\Domain\Bundles\Feed\Search\Criteria\Criteria;

use CASS\Domain\Bundles\Feed\Search\Criteria\Criteria;

final class SortCriteria implements Criteria
{
    const CODE_STRING = 'sort';

    /** @var string */
    private $field;

    /** @var string */
    private $order;

    public function getCode(): string
    {
        return self::CODE_STRING;
    }

    public function unpack(array $criteria)
    {
        $this->field = $criteria['field'];
        $this->order = $criteria['order'];
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOrder(): string
    {
        return $this->order;
    }
}