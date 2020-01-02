<?php 
namespace Emizentech\ShopByBrand\Block;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

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
    protected $_request;


	public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Emizentech\ShopByBrand\Model\BrandFactory $brandFactory,
        \Magento\Framework\App\Request\Http $request,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext = $httpContext;
        $this->_imageHelper = $context->getImageHelper();
        $this->_brandFactory = $brandFactory;
        $this->_cartHelper = $context->getCartHelper();
        $this->_request = $request;
        parent::__construct(
            $context,
            $data
        );
	$this->setCollection($this->getProductCollection());
    }
	 public function getAddToCartUrl($product, $additional = [])
    {
			return $this->_cartHelper->getAddUrl($product, $additional);
    }
    
    
    public function _prepareLayout()
    {
        parent::_prepareLayout();
	/** @var \Magento\Theme\Block\Html\Pager */
        $pager = $this->getLayout()->createBlock(
           'Magento\Theme\Block\Html\Pager',
           'brand.view.pager'
        );
        $pager->setLimit(12)
            ->setShowAmounts(false)
            ->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
 
        return $this;
    }
	/**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    public function getBrand(){ 

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
		$collection->addAttributeToSelect('name');
    	$collection->addStoreFilter()->addAttributeToFilter('manufacturer' , $brand->getAttributeId());	
    	$collection->addAttributeToFilter('status', Status::STATUS_ENABLED);
    	$collection->addAttributeToFilter('visibility', array('neq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_NOT_VISIBLE));
    	return $collection;
    }
    
    public function imageHelperObj(){
        return $this->_imageHelper;
    }
    
    public function getProductPricetoHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null
	) {
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product
            );
        }
        return $price;
    }
}
