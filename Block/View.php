<?php
namespace Emizentech\ShopByBrand\Block;
class View extends \Magento\Framework\View\Element\Template
{
	
	/**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;
    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;
    
	/**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    
    /**
     * Image helper
     *
     * @var Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;
     /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;

    protected $_brandFactory;


	public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Emizentech\ShopByBrand\Model\BrandFactory $brandFactory,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext = $httpContext;
        $this->_imageHelper = $context->getImageHelper();
        $this->_brandFactory = $brandFactory;
        $this->_cartHelper = $context->getCartHelper();
        parent::__construct(
            $context,
            $data
        );
    }
	 public function getAddToCartUrl($product, $additional = [])
    {
			return $this->_cartHelper->getAddUrl($product, $additional);
    }
    
    
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    public function getBrand(){
	   //  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//     	$model = $objectManager->create(
//             'Magento\Catalog\Model\ResourceModel\Eav\Attribute'
//         )->setEntityTypeId(
//             \Magento\Catalog\Model\Product::ENTITY
//         );
// 
// 		$model->loadByCode(\Magento\Catalog\Model\Product::ENTITY,'manufacturer');
// 		return $model->getOptions();
		$id = $this->getRequest()->getParam('id');
        if ($id) {
        	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$model = $objectManager->create('Emizentech\ShopByBrand\Model\Items');
			$model->load($id);
			return $model;
		}
		return false;
    }
    
    public function getProductCollection()
    {
    	$brand = $this->getBrand();
    	$collection = $this->_productCollectionFactory->create();
    	$collection->addAttributeToSelect('*');
//     	var_dump(get_class_methods($collection));
//     	die;
		$collection->addAttributeToSelect('name');
    	$collection->addStoreFilter()->addAttributeToFilter('manufacturer' , $brand->getAttributeId());
//     	var_dump(count($collection));
    	return $collection;
    }
    
    public function imageHelperObj(){
        return $this->_imageHelper;
    }
     /**
     * Return HTML block with price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['zone'] = isset($arguments['zone'])
            ? $arguments['zone']
            : $renderZone;
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;
            /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        
        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                $arguments
            );
        }
        return $price;
    }
}