<?php

namespace OuterEdge\CategoryAttributeFilter\Observer;

use Magento\Catalog\Model\Category;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use OuterEdge\CategoryAttributeFilter\Model\FilterableAttributesFactory;

class CategorySaveFilterableAttributesObserver implements ObserverInterface
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
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var Category $category */
        $category = $observer->getEvent()->getCategory();
        $data = $observer->getEvent()->getRequest()->getPostValue();

        if ($category->getId() && isset($data['filterable_attributes'])) {
                      
            $model = $this->filterableAttributesFactory->create();
            $collection = $model->getCollection();
            $collection->addFieldToFilter('category_id', ['eq'=> $category->getId()]);
            
            //Delete all row from this category
            $collection->walk('delete');

            //Save new rows
            foreach ($data['filterable_attributes'] as $row) {
                $saveRow = [
                            'category_id' => $category->getId(), 
                            'eav_attribute_id' => $row 
                        ];
        
                $model->addData($saveRow);  
               
                try {
                    $model->save();
                    $model->unsetData();
                   
                } catch (Exception $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
        }
    }
}
