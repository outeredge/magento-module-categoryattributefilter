<?php
 
namespace OuterEdge\CategoryAttributeFilter\Plugin;
  
use OuterEdge\CategoryAttributeFilter\Model\FilterableAttributesFactory;

class CategoryDataProviderPlugin {
    
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
     * Added filterable_id field to ui dataScope
     * After getData 
     */
    public function afterGetData(\Magento\Catalog\Model\Category\DataProvider $subject, $result)
	{
        $model = $this->filterableAttributesFactory->create();
        $data = reset($result);
        
        $collection = $model->getCollection();
        if(isset($data['entity_id'])) {
            $collection->addFieldToFilter('category_id', ['eq' => $data['entity_id']]);
        }
        
        $selectedIds = array();
        foreach ($collection as $row) {
            $selectedIds[] = $row->getEavAttributeId(); 
        }

        $result[key($result)]['filterable_attributes'] = $selectedIds;
        
		return $result;
	}
	
}