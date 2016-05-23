<?php
namespace Emizentech\ShopByBrand\Block;

class ProductBrand extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var _brandFactory
	 */
    protected $_brandFactory;
	/**
	 * @var Registry
	 */
	private $registry;
	
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Emizentech\ShopByBrand\Model\BrandFactory $brandFactory,
        \Magento\Framework\Registry $registry
    ) 
    {
    	$this->_brandFactory = $brandFactory;
    	$this->registry = $registry;
        parent::__construct($context);
    }
    
    public function getImageMediaPath(){
    	return $this->getUrl('pub/media',['_secure' => $this->getRequest()->isSecure()]);
    }
    
    public function getBrand(){
	    $product = $this->registry->registry('current_product');
	    $collection = $this->_brandFactory->create()->getCollection();
		$collection->addFieldToFilter('attribute_id' , $product->getManufacturer());
	    return $collection->getFirstItem();;
    }
}