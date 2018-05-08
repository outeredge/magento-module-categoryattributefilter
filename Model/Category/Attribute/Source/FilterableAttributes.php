<?php

namespace OuterEdge\CategoryAttributeFilter\Model\Category\Attribute\Source;

use Magento\Framework\Option\ArrayInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

class FilterableAttributes implements ArrayInterface
{
    /**
     * ResourceConnection
     *
     * @var ResourceConnection
     */
    protected $_resourceConnection;
    
    /**
     * CollectionFactory
     *
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param ResourceConnection $resourceConnection
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        CollectionFactory $collectionFactory
    )
    {
        $this->_resourceConnection = $resourceConnection;
        $this->_collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $connection = $this->_resourceConnection->getConnection();
        
        $select = $connection->select()->from(['ea' => $connection->getTableName('eav_attribute')], 'ea.attribute_id')
            ->join(['eea' => $connection->getTableName('eav_entity_attribute')], 'ea.attribute_id = eea.attribute_id')
            ->join(['cea' => $connection->getTableName('catalog_eav_attribute')], 'ea.attribute_id = cea.attribute_id')
            ->join(['cpe' => $connection->getTableName('catalog_product_entity')], 'eea.attribute_set_id = cpe.attribute_set_id')
            ->join(['ccp' => $connection->getTableName('catalog_category_product')], 'cpe.entity_id = ccp.product_id')
            ->where('cea.is_filterable = ?', 1)
            //->where('ccp.category_id = ?', $categoryId)
            ->group('ea.attribute_id');
        
        $attributeIds = $connection->fetchCol($select);
        
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection */
        $collection = $this->_collectionFactory->create();
        $collection->setItemObjectClass('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
        $collection->addFieldToFilter('main_table.attribute_id', ['in' => $attributeIds]);
        $collection->setOrder('frontend_label','ASC');
        
        $attr_groups = array();
        foreach ($collection as $items) {
            $attr_groups[] = [
                'value' => $items->getId(), 
                'label' => $items->getFrontendLabel()
            ];
        }
        
        return $attr_groups;
    }
}
