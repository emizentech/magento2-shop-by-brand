<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
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
    
 //    public function __construct(
//        \Magento\Backend\App\Action\Context $context,
//        \Magento\Framework\Registry $registry,
//        \Magento\Backend\Model\View\Result\ForwardFactory $ForwardFactory , 
//        \Magento\Framework\View\Result\PageFactory $PF,
//     	\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
//         array $data = []
//     ) {
//         parent::__construct($context, $data);
//         $this->_attrOptionCollectionFactory = $attrOptionCollectionFactory;
//         
//     }

    public function execute()
    {
//         $this->_forward('edit');
		 $model = $this->_objectManager->create(
            'Magento\Catalog\Model\ResourceModel\Eav\Attribute'
        )->setEntityTypeId(
            \Magento\Catalog\Model\Product::ENTITY
        );

       $model->loadByCode(\Magento\Catalog\Model\Product::ENTITY,'manufacturer');
        
//         echo "<pre>";
//         var_dump(get_class_methods($model));
		foreach($model->getOptions() as $option){
			//var_dump($option->debug());
			
			$item = $this->_objectManager->create('Emizentech\ShopByBrand\Model\Items');
			if($option->getValue()){			
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
// 				var_dump($item->debug());
		try{
				$item->save();
		}
		catch(\Exception $e){
		
		}
// 				var_dump($item->debug());
// 				die;
				
			}
		}
		$this->messageManager->addSuccess(__('All Brands Re-Synced'));
		$this->_redirect('emizentech_shopbybrand/*/');
	
    }
}
