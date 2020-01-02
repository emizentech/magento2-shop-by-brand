<?php
namespace Emizentech\ShopByBrand\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Layer extends \Magento\Catalog\Model\Layer
{
    protected $_request;

    public function __construct(
        \Magento\Catalog\Model\Layer\ContextInterface $context,
        \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $catalogProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\Request\Http $request,

        array $data = []
    ) {
       
        $this->productCollectionFactory = $productCollectionFactory;
        $this->_request = $request;
        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
    }

    public function getBrand(){ 
        $brand_params = $this->_request->getParams();
        $id = $this->_request->getParam('id');
        if ($id) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $model = $objectManager->create('Emizentech\ShopByBrand\Model\Items');     
            if (array_key_exists("manufacturer",$brand_params)){
                $manufacturer = $this->_request->getParam('manufacturer');
                $model->load($manufacturer,'attribute_id');
            }
            else{
               $model->load($id);
            } 
            return $model;
        }
        return false;
    }

    public function getProductCollection()
    {       
        if (isset($this->_productCollections['brand_collection'])) {
            $collection = $this->_productCollections['brand_collection'];
        } 
        else {
            //here you assign your own custom collection of products
            $collection = $this->productCollectionFactory->create();
            $this->prepareProductCollection($collection);
            $this->_productCollections['brand_collection'] = $collection;

            $brand = $this->getBrand();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToSelect('name');
            $collection->addStoreFilter()->addAttributeToFilter('manufacturer' , $brand->getAttributeId());
            
            $collection->addAttributeToFilter('status', Status::STATUS_ENABLED);
            $collection->addAttributeToFilter('visibility', array('neq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE));
        }
  
        return $collection;
    }

}