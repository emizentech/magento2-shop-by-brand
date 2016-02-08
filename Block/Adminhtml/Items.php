<?php
/**
 * Copyright © 2015 Emizentech. All rights reserved.
 */
namespace Emizentech\ShopByBrand\Block\Adminhtml;

class Items extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'items';
        $this->_headerText = __('Items');
        $this->_addButtonLabel = __('Re-Sync Brands');
        parent::_construct();
    }
}
