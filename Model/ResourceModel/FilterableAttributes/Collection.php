<?php

namespace OuterEdge\CategoryAttributeFilter\Model\ResourceModel\FilterableAttributes;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'OuterEdge\CategoryAttributeFilter\Model\FilterableAttributes', 
            'OuterEdge\CategoryAttributeFilter\Model\ResourceModel\FilterableAttributes'
        );
    }
}
