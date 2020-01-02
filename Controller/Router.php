<?php
namespace Emizentech\ShopByBrand\Controller;

/**
 * Emizentech Custom router Controller Router
 *
 * @author Amit samsukha<info@emizentech.com>
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(\Magento\Framework\App\ActionFactory $actionFactory, \Magento\Framework\App\ResponseInterface $response)
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }

    /**
     * Validate and Match
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        /*
         * We will search “examplerouter” and “exampletocms” words and make forward depend on word
         * -examplerouter will forward to base router to match inchootest front name, test controller path and test controller class
         * -exampletocms will set front name to cms, controller path to page and action to view
        */

        $identifier = trim($request->getPathInfo() , '/');

        if (strpos($identifier, 'brand/view/index/id') !== false)
        {
            // called via id
            return null;
        }
        else if (strpos($identifier, 'brand/') !== false)
        {
            $patharr = explode("/", $identifier);
            $urlpath = end($patharr);
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $modelcollection = $objectManager->get('\Emizentech\ShopByBrand\Model\BrandFactory')
                ->create()
                ->getCollection();
            $modelcollection->addFieldToFilter('url_key', $urlpath);
            if ($modelcollection->count() >= 1 && $brand = $modelcollection->getFirstItem())
            {
                $request->setModuleName('brand')
                    ->setControllerName('view')
                    ->setActionName('index')
                    ->setParam('id', $brand->getId());
                $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                $request->setAlias(\Magento\Framework\UrlInterface::REWRITE_REQUEST_PATH_ALIAS, '/' . $identifier);
                $request->setPathInfo('/' . $identifier);
                return;
            }
            else
            {
                // not found any URL Key
                return null;
            }

        }
        else
        {
            //There is no match
            return null;
        }
    }
}

