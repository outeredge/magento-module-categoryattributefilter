<?php

namespace OuterEdge\CategoryAttributeFilter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\CategoryAttributeFilter\Model\FilterableAttributesFactory;

class Data extends AbstractHelper
{
    /**
     * @var FilterableAttributesFactory
     */
    protected $filterableAttributesFactory;

    /**
     * @param FilterableAttributesFactory $filterableAttributesFactory
     */
    public function __construct(
        FilterableAttributesFactory $filterableAttributesFactory
    ) {
        $this->filterableAttributesFactory = $filterableAttributesFactory;
    }

    /**
     * Return filterable attributes id array
     */
    public function getFilterableAttrByCatId($catId)
    {
        $selectedIds = array();
        
        if ($catId) {
            $model = $this->filterableAttributesFactory->create();
              
            $collection = $model->getCollection();
            $collection->addFieldToFilter('category_id', ['eq'=> $catId]);
                
            foreach ($collection as $row) {
                $selectedIds[] = $row->getEavAttributeId(); 
            }
        }
        
        return $selectedIds;
    }
}
