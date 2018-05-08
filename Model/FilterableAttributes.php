<?php

namespace OuterEdge\CategoryAttributeFilter\Model;

use Magento\Framework\Model\AbstractModel;

class FilterableAttributes extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\CategoryAttributeFilter\Model\ResourceModel\FilterableAttributes');
    }
}
