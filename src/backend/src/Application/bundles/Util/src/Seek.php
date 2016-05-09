<?php
namespace Application\Util;

class Seek
{
    /** @var int */
    private $maxLimit;

    /** @var int */
    private $offset;

    /** @var int */
    private $limit;

    public function __construct(int $maxLimit, int $offset, int $limit)
    {
        if($limit > $maxLimit) {
            throw new \OutOfBoundsException(sprintf('Max limit exceed, limit: %d, maxLimit: %d', $limit, $maxLimit));
        }

        if($offset < 0) {
            throw new \Exception('Offset should me more than zero');
        }

        $this->maxLimit = $maxLimit;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getMaxLimit()
    {
        return $this->maxLimit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}