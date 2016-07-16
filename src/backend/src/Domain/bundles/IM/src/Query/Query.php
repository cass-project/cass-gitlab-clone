<?php
namespace Domain\IM\Query;

use Domain\IM\Query\Options\OptionsManager;
use Domain\IM\Query\Criteria\CriteriaManager;
use Domain\IM\Query\Source\Source;

final class Query
{
    /** @var Source */
    private $source;
    
    /** @var CriteriaManager */
    private $criteria;
    
    /** @var OptionsManager */
    private $options;

    /**
     * Query constructor.
     * @param Source $source
     * @param CriteriaManager $criteria
     * @param OptionsManager $options
     */
    public function __construct(Source $source, CriteriaManager $criteria, OptionsManager $options)
    {
        $this->source = $source;
        $this->criteria = $criteria;
        $this->options = $options;
    }
    
    public function getSource(): Source
    {
        return $this->source;
    }
    
    public function getCriteria(): CriteriaManager
    {
        return $this->criteria;
    }
    
    public function getOptions(): OptionsManager
    {
        return $this->options;
    }
}