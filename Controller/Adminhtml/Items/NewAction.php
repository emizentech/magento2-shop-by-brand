<?php
/**
 * Copyright © 2020 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml\Items;
use Emizentech\ShopByBrand\Model\Items;

class NewAction extends \Emizentech\ShopByBrand\Controller\Adminhtml\Items
{
	/**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory
     */
    protected $_attrOptionCollectionFactory;
    
    protected  $registry;

    public function execute()
    {
		 $model = $this->_objectManager->create(
            'Magento\Catalog\Model\ResourceModel\Eav\Attribute'
        )->setEntityTypeId(
            \Magento\Catalog\Model\Product::ENTITY
        );
       $model->loadByCode(\Magento\Catalog\Model\Product::ENTITY,'manufacturer');
		foreach($model->getOptions() as $option)
		{
			$item = $this->_objectManager->create('Emizentech\ShopByBrand\Model\Items');
			if($option->getValue())
			{			
				$id = (int)$option->getValue();
				if ($id) {
                    $item->load($id);
                    if ($id != $item->getId()) {
//                         throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
				
				$data = array(
					'name' => $option->getLabel(),
					'attribute_id' => $option->getValue(),
					'is_active' => 1,
				);			
				$item->setData($data);
				try{
						$item->save();
				}
				catch(\Exception $e){
				
				}				
			}
		}
		$this->messageManager->addSuccess(__('All Brands Re-Synced'));
		$this->_redirect('emizentech_shopbybrand/*/');	
    }
}
