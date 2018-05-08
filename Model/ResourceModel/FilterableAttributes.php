<?php

namespace OuterEdge\CategoryAttributeFilter\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class FilterableAttributes extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('catalog_category_filterable_attributes', 'id');
    }
}
