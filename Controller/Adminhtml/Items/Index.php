<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */

namespace Emizentech\ShopByBrand\Controller\Adminhtml\Items;

class Index extends \Emizentech\ShopByBrand\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('emizentech::base');
        $resultPage->getConfig()->getTitle()->prepend(__('Emizentech ShopByBrand'));
        $resultPage->addBreadcrumb(__('Emizentech'), __('Emizentech'));
        $resultPage->addBreadcrumb(__('Items'), __('ShopByBrand'));
        return $resultPage;
    }
}
