<?php

namespace OuterEdge\CategoryAttributeFilter\Model\Layer\Category;

use Magento\Catalog\Model\Layer\Category\FilterableAttributeList as CoreFilterableAttributeList;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\Layer\FilterableAttributeListInterface;
use OuterEdge\CategoryAttributeFilter\Helper\Data as HelperData;

class FilterableAttributeList extends CoreFilterableAttributeList
{
    /**
     * var Registry
     */
    protected $registry;
    
    /**
     * var HelperData
     */
    protected $helper;
    
    /**
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Registry $registry
     * @param HelperData $helper
     * @param array $filters
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        Registry $registry,
        HelperData $helper
    ) {
        $this->registry = $registry;
        $this->helper = $helper;

        parent::__construct(
            $collectionFactory, 
            $storeManager
        );
    }
    
    /**
     * Retrieve list of filterable attributes
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    public function getList()
    {   
        $categoryId = $this->registry->registry('current_category')->getId();
	    $attributeIdsToShow = $this->helper->getFilterableAttrByCatId($categoryId);
        
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection */
        $collection = $this->collectionFactory->create();
        $collection->setItemObjectClass('Magento\Catalog\Model\ResourceModel\Eav\Attribute')
            ->addStoreLabel($this->storeManager->getStore()->getId())
            ->setOrder('position', 'ASC');
        $collection = $this->_prepareAttributeCollection($collection);
        $collection->addFieldToFilter('main_table.attribute_id', ['in'=> $attributeIdsToShow]);
        $collection->setOrder('frontend_label', 'ASC');
        $collection->load();

        return $collection;
    }
}
